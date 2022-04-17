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
 * Version:           1.3.2
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            DevApps Consultoria e Desenvolvimento de Software
 * Author URI:        https://devapps.com.br
 * Text Domain:       cenp-mean
 * 
 * GitHub Plugin URI: https://github.com/devappsteam/cenp-meios
 * 
 */

// Verifica o acesso direto ao arquivo.
defined('ABSPATH') || exit;

// Constantes
define('CM_VERSION', '1.3.2');
define('CM_TEXT_DOMAIN', 'cenp-mean');
define('CM_PATH_ROOT', plugin_basename(__FILE__));
define('CM_PATH', plugin_dir_path(__FILE__));
define('CM_TEMPLATE_DIR', 'cenp');

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

  include_once("wp-config.php");
  include_once("wp-includes/wp-db.php");

  $version = get_option('cm_version', '0.0.1');

  $table_region                     = $wpdb->prefix . "cm_states_region";
  $table_spreadsheet                = $wpdb->prefix . "cm_spreadsheets";
  $table_spreadsheet_means          = $wpdb->prefix . "cm_spreadsheets_means";
  $table_spreadsheet_means_regions  = $wpdb->prefix . "cm_spreadsheets_means_regions";
  $table_spreadsheet_regions        = $wpdb->prefix . "cm_spreadsheets_regions";
  $table_spreadsheet_states         = $wpdb->prefix . "cm_spreadsheets_states";
  $table_spreadsheet_ranking        = $wpdb->prefix . "cm_spreadsheets_ranking";
  $table_spreadsheet_ranking_state  = $wpdb->prefix . "cm_spreadsheets_ranking_state";
  $table_agencies                   = $wpdb->prefix . "cm_spreadsheets_agencies";
  $charset_collate                  = $wpdb->get_charset_collate();

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

  $sql = "CREATE TABLE `$table_spreadsheet_ranking`(
    `ID` BIGINT NOT NULL AUTO_INCREMENT,
    `post_id` BIGINT NOT NULL,
    `position` VARCHAR(5) NULL,
    `name` VARCHAR(150) NULL DEFAULT '',
    `state` VARCHAR(5) NULL DEFAULT '',
    PRIMARY KEY (`ID`)
  ) $charset_collate;";

  $sql = "CREATE TABLE `$table_spreadsheet_ranking_state`(
    `ID` BIGINT NOT NULL AUTO_INCREMENT,
    `post_id` BIGINT NOT NULL,
    `position` VARCHAR(5) NULL,
    `name` VARCHAR(150) NULL DEFAULT '',
    `state` VARCHAR(5) NULL DEFAULT '',
    PRIMARY KEY (`ID`)
  ) $charset_collate;";

  dbDelta($sql);

  $sql = "CREATE TABLE `$table_agencies`(
    `ID` BIGINT NOT NULL AUTO_INCREMENT,
    `post_id` BIGINT NOT NULL,
    `name` VARCHAR(150) NULL DEFAULT '',
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

  if (version_compare($version, CM_VERSION, '<')) {
    $sql = "ALTER TABLE `$table_spreadsheet_ranking` 
    ADD COLUMN `last_position` VARCHAR(5) NULL DEFAULT NULL;";
    $wpdb->query($sql);
    update_option('cm_version', CM_VERSION);
  }
}

function init_cenp_meios()
{
  return Cenp_Meios::instance();
}
add_action('plugins_loaded', 'init_cenp_meios', 20);
