<?php

/**
 * Plugin Name:          Cenp Meios
 * Plugin URI:           https://github.com/devappsteam/cenp-meios
 * Description:          Plug-in responsável por efetuar a importação dos dados através de um XLSX.
 * Author:               DevApps Consultoria e Desenvolvimento
 * Author URI:           https://devapps.com.br
 * Version:              1.0.0
 * License:              GPLv2 or later
 * Text Domain:          cenp-meios
 * Domain Path:          /languages
 * 
 * @package Cenp_Meios
 */

use Cenp_Meios\Cenp_Meios_PostType;
use Cenp_Meios\Cenp_Meios_Taxonomy;

// Verifica se houve um acesso direto
defined('ABSPATH') || exit;

// Constantes 
define('CM_VERSION', '1.0.0');
define('CM_PLUGIN_FILE', __FILE__);
define('CM_TEXT_DOMAIN', 'cenp-meios');

require_once('vendor/autoload.php');

if (!class_exists('Cenp_Meios')) {

  class Cenp_Meios
  {
    /**
     * The unique instance of the plugin.
     *
     * @var Cenp_Meios
     */
    private static $instance;

    private $wpdb;

    /**
     * Gets an instance of our plugin.
     *
     * @return Cenp_Meios
     */
    public static function get_instance()
    {
      if (null === self::$instance) {
        self::$instance = new self();
      }

      return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
      global $wpdb;

      $this->wpdb = $wpdb;

      add_action('plugins_loaded', array($this, 'init'));
      register_activation_hook(__FILE__, array($this, 'active'));
    }

    public function init()
    {
      // tradução
      add_action('init', array($this, 'load_plugin_textdomain'), -1);

      // Actions
      add_action('wp_enqueue_scripts', array($this, 'enqueue_script'));
      add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
      add_action('post_edit_form_tag', array($this, 'form_post'));
      add_action('save_post_cenp_import', array($this, 'save_post'), 10, 3);

      // Registra os post types
      $this->register_post_type();

      // Registra as taxonomias
      $this->register_taxonomies();

      $this->register_metabox();

      // Shortcode
      add_shortcode('cenp-meios', array($this, 'create_shortcode'));

      // Ajax
      add_action('wp_ajax_cm_find_post_by_id', array($this, 'find_post_by_id'));
      add_action('wp_ajax_nopriv_cm_find_post_by_id', array($this, 'find_post_by_id'));
    }

    public function active()
    {
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

      $table_region = $this->wpdb->prefix . "cm_states_region";
      $table_spreadsheet = $this->wpdb->prefix . "cm_spreadsheets";
      $charset_collate = $this->wpdb->get_charset_collate();

      $sql = "CREATE TABLE IF NOT EXISTS `$table_region`(
        `ID` TINYINT NOT NULL AUTO_INCREMENT,
        `state` VARCHAR(50) NOT NULL,
        `region` TINYINT(1) NOT NULL,
        PRIMARY KEY (`ID`)
      ) $charset_collate;";

      dbDelta($sql);

      $sql = "CREATE TABLE IF NOT EXISTS `$table_spreadsheet`(
        `ID` BIGINT NOT NULL AUTO_INCREMENT,
        `post_id` BIGINT NOT NULL,
        `state` VARCHAR(50) NOT NULL,
        `mean` VARCHAR(20) NOT NULL,
        `real` DECIMAL(20,2) NOT NULL DEFAULT 0,
        `dollar` DECIMAL(20,2) NOT NULL DEFAULT 0,
        PRIMARY KEY (`ID`)
      ) $charset_collate;";

      dbDelta($sql);

      $sql = "INSERT INTO `$table_region` (`state`,`region`) VALUES
      ('Acre', 1),
      ('Alagoas', 2),
      ('Amazonas', 1),
      ('Amapá', 1),
      ('Bahia', 2),
      ('Ceará', 2),
      ('Distrito Federal',3),
      ('Espírito Santo', 4),
      ('Goiás', 3),
      ('Maranhão', 2),
      ('Minas Gerais', 4),
      ('Mato Grosso do Sul', 3),
      ('Mato Grosso', 3),
      ('Pará', 1),
      ('Paraíba', 2),
      ('Pernambuco', 2),
      ('Piauí', 2),
      ('Paraná', 5),
      ('Rio de Janeiro', 4),
      ('Rio Grande do Norte', 2),
      ('Rondônia', 1),
      ('Roraima', 1),
      ('Rio Grande do Sul', 5),
      ('Santa Catarina', 5),
      ('Sergipe', 2),
      ('São Paulo', 4),
      ('Tocantins', 1);";

      dbDelta($sql);
    }


    /**
     * Enqueues our kickass scripts and stylesheets.
     */
    public function enqueue_script()
    {
      wp_enqueue_style('cm-main-style', plugins_url() . '/cenp-meios/assets/css/style.css', array(), CM_VERSION . '.' . time(), 'all');
      wp_enqueue_script('cm-google-chart', '//www.gstatic.com/charts/loader.js', array(), CM_VERSION, true);
      wp_enqueue_script('cm-main-script', plugins_url() . '/cenp-meios/assets/js/main.js', array('jquery'), CM_VERSION . '.' . time(), true);
      wp_localize_script('cm-main-script', 'cenp_obj', array(
        'ajax_url' => admin_url('admin-ajax.php')
      ));
    }

    public function admin_scripts()
    {
      global $typenow;
      if ($typenow != 'cenp_import') {
        return;
      }

      wp_enqueue_style('cm-admin-style', plugins_url() . '/cenp-meios/assets/css/admin.css', array(), CM_VERSION . '.' . time(), 'all');
      wp_enqueue_script('cm-xlsx', 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.3/xlsx.full.min.js', array('jquery'), CM_VERSION, true);
      wp_enqueue_script('cm-validate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js', array('jquery'), CM_VERSION, true);
      wp_enqueue_script('cm-validate-additional', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js', array('jquery'), CM_VERSION, true);
      wp_enqueue_script('cm-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), CM_VERSION, true);
      wp_enqueue_script('cm-admin-script', plugins_url() . '/cenp-meios/assets/js/admin.js', array('jquery', 'cm-xlsx', 'cm-mask', 'cm-validate', 'cm-validate-additional'), CM_VERSION . '.' . time(), true);
    }

    /**
     * Carregua o domínio de texto do plugin para tradução.
     */
    public function load_plugin_textdomain()
    {
      load_plugin_textdomain('cenp-meios', false, dirname(plugin_basename(CM_PLUGIN_FILE)) . '/languages/');
    }

    private function register_post_type()
    {
      $cenp_import = new Cenp_Meios_PostType('cenp_import');
      $cenp_import->icon('dashicons-cloud-upload');
      $cenp_import->taxonomy('cenp_categories');
      $cenp_import->options([
        'supports' => [
          'title', 'author'
        ]
      ]);
      $cenp_import->labels([
        'add_new_item'  => __('Adicionar Planilha', CM_TEXT_DOMAIN),
        'add_new'       => __('Adicionar', CM_TEXT_DOMAIN),
      ]);
      $cenp_import->register();
      $cenp_import->flush();
    }

    private function register_taxonomies()
    {

      $names = [
        'name'      => 'cenp_categories',
        'singular'  => __('Categoria', CM_TEXT_DOMAIN),
        'plural'    => __('Categorias', CM_TEXT_DOMAIN),
        'slug'      => 'cenp_categories'
      ];

      $options = [
        'hierarchical' => true,
      ];

      $labels = [
        'add_new_item'  => __('Nova Categoria', CM_TEXT_DOMAIN),
      ];

      $category = new Cenp_Meios_Taxonomy($names, $options, $labels);
      $category->register();
    }

    private function register_metabox()
    {
      add_action('add_meta_boxes', function () {
        add_meta_box(
          'cmi_spreadsheet',
          __('Planilha', CM_TEXT_DOMAIN),
          array($this, 'cmi_spreadsheet'),
          'cenp_import',
          'normal',
          'high'
        );
      });
    }

    public function form_post()
    {
      global $typenow;
      if ($typenow == 'cenp_import') {
        echo ' enctype="multipart/form-data" data-validation';
      }
    }

    public function save_post($post_id, $post, $update)
    {
      if ($_POST['post_type'] == 'cenp_import') {
        $data = array(
          'period' => $_POST['ci-period'],
          'description' => $_POST['ci-description'],
          'type' => $_POST['ci-type'],
          'file-json' => $_POST['ci-file-json'],
          'source' => $_POST['ci-source'],
          'note' => $_POST['ci-note'],
        );

        if ($update && (file_exists($_FILES['ci-file']['tmp_name']) || is_uploaded_file($_FILES['ci-file']['tmp_name']))) {
          $this->delete_spreadsheet_by_post($post_id);
          $this->insert_spreadsheet_by_post($post_id, $this->base64_to_array($_POST['ci-file-json']));
        }
        update_post_meta($post_id, 'cmi_spreadsheet', $data);
      }
    }

    private function delete_spreadsheet_by_post(int $post_id)
    {
      $table_spreadsheet = $this->wpdb->prefix . "cm_spreadsheets";
      $this->wpdb->delete($table_spreadsheet, array('post_id' => intval($post_id)));
    }

    private function base64_to_array(string $value)
    {
      return json_decode(utf8_encode(base64_decode($value)), true);
    }

    private function insert_spreadsheet_by_post(int $post_id, array $values)
    {
      $data = array_map(function ($item) use ($post_id) {
        return "($post_id,'" . $item['uf'] . "','" . $item['meio'] . "','" . $item['real'] . "','" . $item['dolar'] . "')";
      }, $values['matriz']);
      array_pop($data);
      $table_spreadsheet = $this->wpdb->prefix . "cm_spreadsheets";
      $sql = "INSERT INTO $table_spreadsheet (`post_id`,`state`,`mean`,`real`,`dollar`) VALUES " . implode(',', $data);
      $this->wpdb->query($sql);
    }

    public function cmi_spreadsheet($post)
    {
      $meta_source = get_post_meta($post->ID, 'cmi_spreadsheet', true);

      $period = '';
      $description = '';
      $json = '';
      $meio = 'checked';
      $ranking = '';
      $ranking_uf = '';
      $source = '';
      $note = '';

      if (!empty($meta_source)) {
        if (!empty($meta_source['period'])) {
          $period = $meta_source['period'];
        }

        if (!empty($meta_source['type'])) {
          switch ($meta_source['type']) {
            case 1:
            default:
              $ranking_uf = '';
              $ranking = '';
              $meio = 'checked';
              break;
            case 2:
              $meio = '';
              $ranking_uf = '';
              $ranking = 'checked';
              break;
            case 3:
              $meio = '';
              $ranking = '';
              $ranking_uf = 'checked';
              break;
          }
        }

        if (!empty($meta_source['description'])) {
          $description = $meta_source['description'];
        }

        if (!empty($meta_source['file-json'])) {
          $json = $meta_source['file-json'];
        }

        if (!empty($meta_source['source'])) {
          $source = $meta_source['source'];
        }

        if (!empty($meta_source['note'])) {
          $note = $meta_source['note'];
        }
      }

      include_once dirname(CM_PLUGIN_FILE) . '/templates/metabox/cm-metabox.php';
    }

    public function create_shortcode()
    {
      $categories = $this->getTaxonomies('cenp_categories');
      include_once dirname(CM_PLUGIN_FILE) . '/templates/shortcode/cm-main.php';
      wp_reset_postdata();
    }

    private function getTaxonomies(string $taxonomy)
    {
      return get_terms(array(
        'taxonomy'         => $taxonomy,
        'hide_empty'     => true,
        'orderBy'        => 'name',
        'order'            => 'DESC',
      ));
    }

    private function getPostsByTaxonomyId(int $taxonomy)
    {
      return query_posts(
        array(
          'post_type' => 'cenp_import',
          'tax_query' => array(
            array(
              'taxonomy' => 'cenp_categories',
              'terms' => $taxonomy,
              'field' => 'term_id',
            )
          ),
          'orderby' => 'title',
          'order' => 'DESC'
        )
      );
    }

    private function getPostById(int $post)
    {
      return get_post($post);
    }

    private function getValuePostMeta(int $post_id, string $key)
    {
      return get_post_meta($post_id, $key, true);
    }

    private function getMonthAndYearByDate(string $date)
    {
      setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
      return strftime('%B/%Y', strtotime($date));
    }

    public function find_post_by_id()
    {
      if (isset($_POST['post']) && !empty($_POST['post'])) {

        $post = $this->getPostById($_POST['post']);

        $metadata = $this->getValuePostMeta($_POST['post'], 'cmi_spreadsheet');
        $period = 1;
        $description = "";
        $source = "";
        $technical_note = "";
        $type = 1;

        if (isset($metadata['source'])) {
          $source = $metadata['source'];
        }

        if (isset($metadata['period'])) {
          $period = $metadata['period'];
        }

        if (isset($metadata['description'])) {
          $description = $metadata['description'];
        }

        if (isset($metadata['note'])) {
          $technical_note = $metadata['note'];
        }

        if (isset($metadata['type'])) {
          $type = $metadata['type'];
        }

        $data = array(
          'logo' => plugins_url() . '/cenp-import/assets/images/logo.png',
          'title' => esc_attr($post->post_title),
          'updated_at' => esc_attr('atualizado em ' . $this->getMonthAndYearByDate($post->post_modified)),
          'description' => $description,
          'type' => $type,
          'period' => $period,
          'source' => $source,
          'technical_note' => $technical_note,
        );

        print_r($data);
      }
      wp_die();
    }

    private function render($data)
    {
      extract($data);
      return include_once('templates/cenp-meio-data.php');
    }
  }
  $cenp_meios = Cenp_Meios::get_instance();
}
