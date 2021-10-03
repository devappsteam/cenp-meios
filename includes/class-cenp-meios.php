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
    add_action('init', array(__CLASS__, 'load_plugin_textdomain'), -1);
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
}
