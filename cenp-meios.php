<?php

/**
 * Cenp Meios
 *
 * @package           CenpMeios
 * @author            Caio Felipe
 * @copyright         2021 DevApps Consultoria e Desenvolvimento de Software
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Cenp Meios
 * Plugin URI:        https://github.com/devappsteam/cenp-meios
 * Description:       Efetua a importação do meios de comunicação atráves de uma matriz XLSX e disponibiliza os dados em uma página atraves de shortcode.
 * Version:           1.0.5
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            DevApps Consultoria e Desenvolvimento de Software
 * Author URI:        https://devapps.com.br
 * Text Domain:       cenp-mean
 */

// Verifica o acesso direto ao arquivo.
defined('ABSPATH') || exit;

// Constantes
define('CM_VERSION', '1.0.5');
define('CM_TEXT_DOMAIN', 'cenp-mean');
define('CM_PATH_ROOT', plugin_basename(__FILE__));

register_activation_hook(
  __FILE__,
  'activate'
);

if (!class_exists('Cenp_Meios')) {
  require_once(dirname(__FILE__) . '/includes/class-main.php');
}

function activate()
{
  global $wpdb;

  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

  $table_region = $wpdb->prefix . "cm_states_region";
  $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets";
  $table_spreadsheet_means = $wpdb->prefix . "cm_spreadsheets_means";
  $table_spreadsheet_means_regions = $wpdb->prefix . "cm_spreadsheets_means_regions";
  $table_spreadsheet_regions = $wpdb->prefix . "cm_spreadsheets_regions";
  $table_spreadsheet_states = $wpdb->prefix . "cm_spreadsheets_states";
  $charset_collate = $wpdb->get_charset_collate();

  $wpdb->query("DROP TABLE IF EXISTS `$table_region`;");

  $sql = "CREATE TABLE `$table_region`(
        `ID` TINYINT NOT NULL AUTO_INCREMENT,
        `state` VARCHAR(50) NOT NULL,
        `region` TINYINT(1) NOT NULL,
        PRIMARY KEY (`ID`)
      ) $charset_collate;";

  dbDelta($sql);

  $sql = "CREATE TABLE `$table_spreadsheet`(
        `ID` BIGINT NOT NULL AUTO_INCREMENT,
        `post_id` BIGINT NOT NULL,
        `state` VARCHAR(50) NULL,
        `mean` VARCHAR(20) NOT NULL,
        `real` DECIMAL(20,2) NOT NULL DEFAULT 0,
        `dollar` DECIMAL(20,2) NOT NULL DEFAULT 0,
        PRIMARY KEY (`ID`)
      ) $charset_collate;";

  dbDelta($sql);

  $sql = "CREATE TABLE `$table_spreadsheet_means`(
    `ID` BIGINT NOT NULL AUTO_INCREMENT,
    `post_id` BIGINT NOT NULL,
    `mean` VARCHAR(20) NOT NULL,
    `real` VARCHAR(20) NOT NULL DEFAULT 0,
    `dollar` VARCHAR(20) NOT NULL DEFAULT 0,
    `share` VARCHAR(20) NOT NULL DEFAULT 0,
    PRIMARY KEY (`ID`)
  ) $charset_collate;";

  dbDelta($sql);

  $sql = "CREATE TABLE `$table_spreadsheet_regions`(
    `ID` BIGINT NOT NULL AUTO_INCREMENT,
    `post_id` BIGINT NOT NULL,
    `region` VARCHAR(20) NOT NULL,
    `real` VARCHAR(20) NOT NULL DEFAULT 0,
    `dollar` VARCHAR(20) NOT NULL DEFAULT 0,
    `share` VARCHAR(20) NOT NULL DEFAULT 0,
    PRIMARY KEY (`ID`)
  ) $charset_collate;";

  dbDelta($sql);

  $sql = "CREATE TABLE `$table_spreadsheet_means_regions`(
    `ID` BIGINT NOT NULL AUTO_INCREMENT,
    `post_id` BIGINT NOT NULL,
    `mean` VARCHAR(20) NOT NULL,
    `real` VARCHAR(20) NOT NULL DEFAULT 0,
    `dollar` VARCHAR(20) NOT NULL DEFAULT 0,
    `share` VARCHAR(20) NOT NULL DEFAULT 0,
    `region` VARCHAR(5) NOT NULL,
    PRIMARY KEY (`ID`)
  ) $charset_collate;";

  dbDelta($sql);

  $sql = "CREATE TABLE `$table_spreadsheet_states`(
    `ID` BIGINT NOT NULL AUTO_INCREMENT,
    `post_id` BIGINT NOT NULL,
    `state` VARCHAR(20) NOT NULL,
    `real` VARCHAR(20) NOT NULL DEFAULT 0,
    `dollar` VARCHAR(20) NOT NULL DEFAULT 0,
    `share` VARCHAR(20) NOT NULL DEFAULT 0,
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

function init_cenp_meios()
{
  return Cenp_Meios::instance();
}
add_action('plugins_loaded', 'init_cenp_meios', 20);
