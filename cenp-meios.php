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

// Verifica se houve um acesso direto
defined('ABSPATH') || exit;

// Constantes 
define('CM_VERSION', '1.0.0');
define('CM_PLUGIN_FILE', __FILE__);
define('CM_TEXT_DOMAIN', 'cenp-meios');

if (!class_exists('Cenp_Meios')) {
  include_once dirname(__FILE__) . '/includes/class-cenp-meios.php';
  add_action('plugins_loaded', array('Cenp_Meios', 'init'));
}
