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
      add_action('plugins_loaded', array($this, 'init'));
    }

    public function init()
    {
      // tradução
      add_action('init', array($this, 'load_plugin_textdomain'), -1);

      // Actions
      add_action('wp_enqueue_scripts', array($this, 'enqueue_script'));

      // Registra os post types
      $this->register_post_type();

      // Registra as taxonomias
      $this->register_taxonomies();
    }


    /**
     * Enqueues our kickass scripts and stylesheets.
     */
    public function enqueue_script()
    {
      // ...
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
  }
  $cenp_meios = Cenp_Meios::get_instance();
}
