<?php

/**
 * Cenp Meios
 *
 * @package Cenp_Meios/Class
 * @since   1.0.0
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * Classe Principal
 */
class Cenp_Meios
{
  /**
   * Inicializa as ações públicas do plugin.
   */
  public static function init()
  {
    // tradução
    add_action('init', array(__CLASS__, 'load_plugin_textdomain'), -1);

    // Registra um novo Post Type
    self::new_post_type();
  }

  /**
   * Carregua o domínio de texto do plugin para tradução.
   */
  public static function load_plugin_textdomain()
  {
    load_plugin_textdomain('cenp-meios', false, dirname(plugin_basename(CM_PLUGIN_FILE)) . '/languages/');
  }

  /**
   * Get main file.
   *
   * @return string
   */
  public static function get_main_file()
  {
    return CM_PLUGIN_FILE;
  }

  /**
   * Get plugin path.
   *
   * @return string
   */
  public static function get_plugin_path()
  {
    return plugin_dir_path(CM_PLUGIN_FILE);
  }

  /**
   * Get templates path.
   *
   * @return string
   */
  public static function get_templates_path()
  {
    return self::get_plugin_path() . 'templates/';
  }

  public static function new_post_type()
  {
    $labels = array(
      'name' => __('Cenp Meios', CM_TEXT_DOMAIN),
      'singular_name' => __('Cenp Meios', CM_TEXT_DOMAIN),
      'manu_name' => __('Cenp Meios', CM_TEXT_DOMAIN),
      'add_new' => __('Adicionar Planilha', CM_TEXT_DOMAIN),
      'add_new_item' => __('Adicionar Nova Planilha', CM_TEXT_DOMAIN),
      'new_item' => __('Nova Planilha', CM_TEXT_DOMAIN),
      'edit_item' => __('Editar Planilha', CM_TEXT_DOMAIN),
      'view_item' => __('Visualizar Planilha', CM_TEXT_DOMAIN),
      'all_items' => __('Todas as Planilhas', CM_TEXT_DOMAIN),
      'search_items' => __('Procurar Planilha', CM_TEXT_DOMAIN),
      'parent_item_colon' => __('Planilhas Filhas', CM_TEXT_DOMAIN),
      'not_found' => __('Nenhuma planilha encontrada!', CM_TEXT_DOMAIN),
      'not_found_in_trash' => __('Nenhuma planilha excluída!', CM_TEXT_DOMAIN)
    );

    $args = array(
      'labels' => $labels,
      'description' => 'Tipo de conteúdo para importações',
      'public' => false,
      'publicly_queryable' => false,
      'query_var' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'rewrite' => array(
        'slug' => 'cenp-meios'
      ),
      'capability_type' => 'post',
      'has_archive' => true,
      'hierarchical' => false,
      'menu_position' => 21,
      'menu_icon' => 'dashicons-cloud-upload',
      'supports' => array(
        'title', 'author'
      )
    );

    register_post_type('cenp-meios', $args);
  }
}
