<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit();
global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "cm_states_region");
