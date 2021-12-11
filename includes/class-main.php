<?php
// Verifica o acesso direto ao arquivo
defined('ABSPATH') || exit;

class Cenp_Meios
{
  private static $_instance = NULL;

  private function __construct()
  {
    $this->text_domain();
    $this->includes();
    $this->init();
  }

  /**
   * Retorna a instancia da classe principal
   *
   * @return object Cenp_Meios
   */
  public static function instance()
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  protected function text_domain()
  {
    load_plugin_textdomain(CM_TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
  }

  protected function includes()
  {
    require_once(dirname(__FILE__) . '/class-helper.php');
    require_once(dirname(__FILE__) . '/class-utils.php');
    require_once(dirname(__FILE__) . '/class-admin.php');
    require_once(dirname(__FILE__) . '/class-front.php');
  }

  protected function init()
  {
    new Cenp_Meios_Admin;
    new Cenp_Meios_Front;
  }
}
