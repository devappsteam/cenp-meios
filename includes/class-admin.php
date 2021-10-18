<?php

defined('ABSPATH') || exit;

class Cenp_Meios_Admin extends Cenp_Meios_Utils
{

  public  function __construct()
  {
    add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    add_action('init', array($this, 'cm_register_post_type'));
    add_action('init', array($this, 'cm_register_taxonomy'));
    add_action('save_post', array($this, 'cm_save'));
    add_filter('mce_buttons_2', array($this, 'cm_mce_buttons_2'));
  }

  public function admin_scripts()
  {
    global $typenow;
    if ($typenow != 'cenp-mean') {
      return;
    }

    wp_enqueue_style(
      'cm_bootstrap',
      '//cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css',
      array(),
      '4.6.0',
      'all'
    );
    wp_enqueue_script(
      'cm_bootstrap',
      '//cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js',
      array('jquery'),
      '4.6.0',
      true
    );
    wp_enqueue_script(
      'cm_maskedinput',
      '//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js',
      array('jquery', 'cm_bootstrap'),
      '1.4.1',
      true
    );
    wp_enqueue_script(
      'cm_xlsx',
      '//cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.3/xlsx.full.min.js',
      array('jquery'),
      CM_VERSION,
      true
    );
    wp_enqueue_script(
      'cm_validate',
      '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js',
      array('jquery'),
      CM_VERSION,
      true
    );
    wp_enqueue_script(
      'cm_validate-additional',
      '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js',
      array('jquery', 'cm_validate'),
      CM_VERSION,
      true
    );
    wp_enqueue_script(
      'cm_scripts',
      apply_filters('cm_scripts_url', plugins_url('assets/js/cenp_meios_admin.js', CM_PATH_ROOT)),
      array('jquery', 'cm_maskedinput', 'cm_xlsx', 'cm_validate-additional'),
      CM_VERSION,
      true
    );
    wp_enqueue_style(
      'cm_style',
      apply_filters('cm_style_url', plugins_url('assets/css/cenp_meios_admin.css', CM_PATH_ROOT)),
      array(),
      CM_VERSION,
      'all'
    );
  }

  public function add_admin_menu_item()
  {
    $page_title = $menu_title = 'Notificações Nota Fiscal';
    $capability = 'manage_options';
    $menu_slug = 'cm_import';
    $icon_url = 'dashicons-cloud-upload';
    $position = '21';

    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
  }

  public function cm_register_post_type()
  {
    $labels = array(
      'name' => __('Cenp Meios', CM_TEXT_DOMAIN),
      'singular_name' => __('Cenp Meio', CM_TEXT_DOMAIN),
      'add_new' => __('Adicionar Novo', CM_TEXT_DOMAIN),
      'add_new_item' => __('Novo Item', CM_TEXT_DOMAIN),
      'edit_item' => __('Editar Item', CM_TEXT_DOMAIN),
      'new_item' => __('Novo Item', CM_TEXT_DOMAIN),
      'view_item' => __('Ver Item', CM_TEXT_DOMAIN),
      'search_items' => __('Procurar Itens', CM_TEXT_DOMAIN),
      'not_found' =>  __('Nenhum registro encontrado', CM_TEXT_DOMAIN),
      'not_found_in_trash' => __('Nenhum registro encontrado na lixeira', CM_TEXT_DOMAIN),
      'parent_item_colon' => '',
      'menu_name' => __('Cenp Meios', CM_TEXT_DOMAIN),
    );

    $args = array(
      'labels'                => $labels,
      'public'                => true,
      'public_queryable'      => true,
      'show_ui'               => true,
      'query_var'             => true,
      'rewrite'               => true,
      'capability_type'       => 'post',
      'has_archive'           => true,
      'hierarchical'          => false,
      'menu_position'         => 21,
      'menu_icon'             => 'dashicons-cloud-upload',
      'register_meta_box_cb'  => array($this, 'cm_register_meta_box'),
      'supports'              => array('title'),
    );

    register_post_type('cenp-mean', $args);
    flush_rewrite_rules();
  }

  public function cm_register_taxonomy()
  {
    $labels = array(
      'name'                => __('Categorias', CM_TEXT_DOMAIN),
      'singular_name'       => __('Categoria', CM_TEXT_DOMAIN),
      'search_items'        => __('Buscar Categorias', CM_TEXT_DOMAIN),
      'all_items'           => __('Todas as Categorias', CM_TEXT_DOMAIN),
      'parent_item'         => __('Categoria Parental', CM_TEXT_DOMAIN),
      'parent_item_colon'   => __('Categoria Parental:', CM_TEXT_DOMAIN),
      'edit_item'           => __('Editar Categoria', CM_TEXT_DOMAIN),
      'update_item'         => __('Atualizar Categoria', CM_TEXT_DOMAIN),
      'add_new_item'        => __('Adicionar Categoria', CM_TEXT_DOMAIN),
      'new_item_name'       => __('Adicionar Categoria', CM_TEXT_DOMAIN),
      'menu_name'           => __('Categorias', CM_TEXT_DOMAIN),
    );
    register_taxonomy(
      'cenp-category',
      'cenp-mean',
      array(
        'hierarchical'        => true,
        'labels'              => $labels,
        'show_ui'             => true,
        'show_admin_column'   => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'cenp-category'),
      )
    );
  }


  public function cm_register_meta_box()
  {
    add_meta_box('meta_box_cm', __('Importar Planilha', CM_TEXT_DOMAIN), array($this, 'meta_box_cm_form'), 'cenp-mean', 'normal', 'high');
  }

  public function meta_box_cm_form($post)
  {
    $form_data = get_post_meta($post->ID, '_meios', true);
    include_once(dirname(dirname(__FILE__)) . '/templates/form-admin.php');
  }

  public function cm_save($post_id)
  {
    if (!isset($_POST['cm_nonce']) || !wp_verify_nonce($_POST['cm_nonce'], 'cm_nonce')) {
      return;
    }

    $form_data = array(
      'cm_period'             => $_POST['cm_period'],
      'cm_type'               => $_POST['cm_type'],
      'cm_description'        => $_POST['cm_description'],
      'cm_source'             => $_POST['cm_source'],
      'cm_note'               => $_POST['cm_note'],
      'cm_agency_title'       => $_POST['cm_agency_title'],
      'cm_agency_text'        => $_POST['cm_agency_text'],
      'cm_spreadsheet_type'   => $_POST['cm_spreadsheet_type'],
      'cm_source_real'        => $_POST['cm_source_real'],
      'cm_source_dollar'      => $_POST['cm_source_dollar'],
      'cm_source_midia'       => $_POST['cm_source_midia'],
      'cm_source_mercado'     => $_POST['cm_source_mercado']
    );

    if (!empty($_POST['cm_json'])) {
      $this->delete_spreadsheet_by_post($post_id);
      $this->insert_spreadsheet_by_post($post_id, $this->base64_to_array($_POST['cm_json']));
    }

    update_post_meta($post_id, '_meios', $form_data);
  }

  private function delete_spreadsheet_by_post(int $post_id)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets";
    $wpdb->delete($table_spreadsheet, array('post_id' => intval($post_id)));
  }

  private function base64_to_array(string $value)
  {
    return json_decode(utf8_encode(base64_decode($value)), true);
  }

  private function insert_spreadsheet_by_post(int $post_id, array $values)
  {
    global $wpdb;
    $data = array_map(function ($item) use ($post_id) {
      return "($post_id,'" . $item['uf'] . "','" . $item['meio'] . "','" . $item['real'] . "','" . $item['dolar'] . "')";
    }, $values['matriz']);
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets";
    $sql = "INSERT INTO $table_spreadsheet (`post_id`,`state`,`mean`,`real`,`dollar`) VALUES " . implode(',', $data);
    $wpdb->query($sql);
  }

  public function cm_mce_buttons_2($buttons)
  {
    $buttons[] = 'superscript';
    $buttons[] = 'subscript';
    $buttons[] = 'code';
    return $buttons;
  }
}
