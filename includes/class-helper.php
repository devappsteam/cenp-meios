<?php

defined('ABSPATH') || exit;

class Helpers
{
  public static function load_view($view, $args = [], $allow_theme_override = TRUE)
  {

    $file = apply_filters("cenp_load_view_$view", $view, $args);
    $args = apply_filters("cenp_load_view_args_$view", $args);

    if ('.php' !== substr($file, -4)) {
      $file .= '.php';
    }

    if ($allow_theme_override) {
      $file = self::locate_template([$file], $file);
    }

    if (is_file($file)) {
      $file_path = $file;
    } else {

      $file_path = CM_PATH . "views/$file";

      if (!is_file($file_path)) {
        return;
      }
    }

    if (!empty($args) && is_array($args)) {
      extract($args);
    }
    @include $file_path;
  }

  protected static function locate_template($possibilities, $default = '')
  {

    $possibilities = apply_filters('cenp_locate_template_possibilities', $possibilities);

    $theme_overrides = array();

    foreach ($possibilities as $p) {
      $theme_overrides[] = CM_TEMPLATE_DIR . "/$p";
    }

    $found = locate_template($theme_overrides, FALSE);
    if ($found) {
      return $found;
    }

    foreach ($possibilities as $p) {

      if (file_exists(CM_PATH . "views/$p")) {
        return CM_PATH . "views/$p";
      }
    }

    return $default;
  }

  public static function load_view_to_string($view, $args = [], $allow_theme_override = TRUE)
  {
    ob_start();
    self::load_view($view, $args, $allow_theme_override);
    return ob_get_clean();
  }
}
