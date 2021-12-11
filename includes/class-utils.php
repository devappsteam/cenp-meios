<?php
// Verifica o acesso direto
defined('ABSPATH') || exit;

class Cenp_Meios_Utils
{

  public function d($data)
  {
    if (is_null($data)) {
      $str = "<i>NULL</i>";
    } elseif ($data == "") {
      $str = "<i>Empty</i>";
    } elseif (is_array($data)) {
      if (count($data) == 0) {
        $str = "<i>Empty array.</i>";
      } else {
        $str = "<table style=\"border-bottom:0px solid #000;\" cellpadding=\"0\" cellspacing=\"0\">";
        foreach ($data as $key => $value) {
          $str .= "<tr><td style=\"background-color:#008B8B; color:#FFF;border:1px solid #000;\">" . $key . "</td><td style=\"border:1px solid #000;\">" . $this->d($value) . "</td></tr>";
        }
        $str .= "</table>";
      }
    } elseif (is_resource($data)) {
      while ($arr = mysql_fetch_array($data)) {
        $data_array[] = $arr;
      }
      $str = $this->d($data_array);
    } elseif (is_object($data)) {
      $str = $this->d(get_object_vars($data));
    } elseif (is_bool($data)) {
      $str = "<i>" . ($data ? "True" : "False") . "</i>";
    } else {
      $str = $data;
      $str = preg_replace("/\n/", "<br>\n", $str);
    }
    return $str;
  }

  public function dnl($data)
  {
    echo $this->d($data) . "<br>\n";
  }

  public function dd($data)
  {
    echo $this->dnl($data);
    exit;
  }

  public function ddt($message = "")
  {
    echo "[" . date("Y/m/d H:i:s") . "]" . $message . "<br>\n";
  }

  public function slugify($text, string $divider = '_')
  {
    
	// normaliza a codificação da string
	$text = utf8_decode($text);
	  
	// replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
	  
    $text = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $text);
	  
    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
  }
}
