<?php
defined('ABSPATH') || exit;

class Cenp_Meios_Front extends Cenp_Meios_Utils
{
  public function __construct()
  {
    add_action('wp_enqueue_scripts', array($this, 'enqueue_script'));
    add_shortcode('cenp-mean', array($this, 'create_shortcode'));

    // Ajax
    add_action('wp_ajax_cm_find_post_by_id', array($this, 'find_post_by_id'));
    add_action('wp_ajax_nopriv_cm_find_post_by_id', array($this, 'find_post_by_id'));
  }

  /**
   * Enqueues our kickass scripts and stylesheets.
   */
  public function enqueue_script()
  {
    wp_enqueue_style('cm_main', plugins_url('assets/css/cenp_meios.css', CM_PATH_ROOT), array(), CM_VERSION, 'all');
    wp_enqueue_script('cm_google_chart', '//www.gstatic.com/charts/loader.js', array(), CM_VERSION, true);
    wp_enqueue_script('cm_main', plugins_url('assets/js/cenp_meios.js', CM_PATH_ROOT), array('jquery', 'cm_google_chart'), CM_VERSION, true);
    wp_localize_script('cm_main', 'cenp_obj', array(
      'ajax_url' => admin_url('admin-ajax.php')
    ));
  }

  public function create_shortcode($attr)
  {
    $attributes = shortcode_atts(array(
      'category' => 'meio',
    ), $attr);

    $is_ranking = ($attributes['category'] == 'ranking') ? true : false;
	if(!$is_ranking){
		$taxonomy = 'cenp-category';
		$categories = $this->getTaxonomies($taxonomy);
	}else{
		$taxonomy = 'cenp-ranking';
		$categories = $this->getRankingsByTaxonomy();
	}
    if (!empty($categories)) {
      foreach ($categories as $key => $value) {
        $categories[$key]->posts = $this->getPostsByTaxonomyId($value->term_id, $taxonomy);
      }
    }
	  
    Helpers::load_view('main', compact('is_ranking', 'categories', 'posts'));
  }
  
  public function getRankingsByTaxonomy(){
	  return get_terms(array(
      'taxonomy'         => 'cenp-ranking',
      'hide_empty'     => true,
      'orderBy'        => 'name',
      'order'            => 'DESC',
	  'meta_query' => array(
             array(
                'key'       => 'show_modal',
                'value'     => 'yes',
                'compare'   => '='
             )
      )
    ));
  }
	
  public function getTaxonomies(string $taxonomy)
  {
    return get_terms(array(
      'taxonomy'         => $taxonomy,
      'hide_empty'     => false,
      'orderBy'        => 'name',
      'order'            => 'DESC',
    ));
  }

  public function getPostsByTaxonomyId(int $taxonomy_id, $taxonomy)
  {
    $posts = query_posts(
      array(
        'post_type' => 'cenp-mean',
        'tax_query' => array(
          array(
            'taxonomy' => $taxonomy,
            'terms' => $taxonomy_id,
            'field' => 'term_id',
          )
        ),
		'orderby' => 'title',
		'order' => 'DESC',
      )
    );
	if(empty($posts)){
		return array();
	}
	 $data = array();
	foreach($posts as $post){
		array_push($data, (object) array(
			'ID' => $post->ID,
			'post_title' => $post->post_title,
			'period'	=> get_post_meta($post->ID, '_meios', true)['cm_period']
		));
	}
	usort($data, function($a, $b) {
		return $b->period <=> $a->period;
	});
	return $data;
  }

  public function find_post_by_id()
  {
    $html = '';
    if (!isset($_POST['post']) || empty($_POST['post'])) {
      echo '<p class="cm-empty">' . __('Post ID não informado ou inválido.', CM_TEXT_DOMAIN) . '</p>';
      wp_die();
    }
    // POST
    $post = get_post(intval($_POST['post']));
    if (empty($post)) {
      echo '<p class="cm-empty">' . __('Nenhum resultado foi encontrado.', CM_TEXT_DOMAIN) . '</p>';
      wp_die();
    }

    $year = get_the_terms($post->ID, 'cenp-category');
    $year = (!empty($year)) ? (int) filter_var($year[0]->name, FILTER_SANITIZE_NUMBER_INT) : '';

    $post_meta = get_post_meta($post->ID, '_meios', true);

    $this->get_data_mean_comunication($post->ID);

    //FILE CONTENT
    $display = '';

    // HTML BY TYPE
    switch ($post_meta['cm_type']) {
      case 1:
      default:
        switch ($post_meta['cm_period']) {
          case 1:
            $table_title = 'JAN-MAR' . '/' . $year;
            $html .= $this->render_mean_comunication($post, $post_meta);
            break;
          case 2:
            $table_title = 'JAN-JUN' . '/' . $year;
            $html .= $this->render_mean_comunication($post, $post_meta);
            $html .= $this->render_region($post, $post_meta);
            break;
          case 3:
            $table_title = 'JAN-SET' . '/' . $year;
            $html .= $this->render_mean_comunication($post, $post_meta);
            $html .= $this->render_region($post, $post_meta);
            break;
          case 4:
            $table_title = 'JAN-DEZ' . '/' . $year;
            $html .= $this->render_mean_comunication($post, $post_meta);
            $html .= $this->render_region($post, $post_meta);
            $html .= $this->render_mean_region($post, $post_meta);
            $html .= $this->render_state($post, $post_meta);
            break;
        }
        break;
      case 2:
		switch ($post_meta['cm_period']) {
          case 1:
            $table_title = 'JAN-MAR' . '/' . $year;
            break;
          case 2:
            $table_title = 'JAN-JUN' . '/' . $year;
            break;
          case 3:
            $table_title = 'JAN-SET' . '/' . $year;
            break;
          case 4:
            $table_title = 'JAN-DEZ' . '/' . $year;
            break;
        }
        $html .= $this->render_ranking($post, $post_meta);
        break;
      case 3:
			switch ($post_meta['cm_period']) {
          case 1:
            $table_title = 'JAN-MAR' . '/' . $year;
            break;
          case 2:
            $table_title = 'JAN-JUN' . '/' . $year;
            break;
          case 3:
            $table_title = 'JAN-SET' . '/' . $year;
            break;
          case 4:
            $table_title = 'JAN-DEZ' . '/' . $year;
            break;
        }
        $html .= $this->render_ranking_uf($post, $post_meta);
        break;
    }

    $html .= $this->render_source($post, $post_meta);

    if ($post_meta['cm_type'] != 2 && $post_meta['cm_type'] != 3) {

      if (!empty($post_meta['cm_note'])) {
        $html .= $this->render_note($post, $post_meta);
      }

      if (!empty($post_meta['cm_description_footer'])) {
        $html .= $this->render_text_footer($post, $post_meta);
      }

      $html .= $this->render_accordion($post, $post_meta);

      $html .= $this->render_tools($post, $post_meta);
    }

    // HTML - SOURCE + NOTE
    $html = str_replace('[TABLE_TITLE]', $table_title, $html);
    $html = str_replace('[SOURCE_MIDIA]', $post_meta['cm_source_midia'], $html);
    $html = str_replace('[SOURCE_MERCADO]', $post_meta['cm_source_mercado'], $html);
    $html = str_replace('[SOURCE_MEIOS_REGIOES]', $post_meta['cm_source_meios_regioes'], $html);

    echo $html;
    wp_die();
  }

  public function render_source($post, $post_meta)
  {
    ob_start();
    Helpers::load_view('source', compact('post', 'post_meta'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_note($post, $post_meta)
  {
    ob_start();
    Helpers::load_view('note', compact('post', 'post_meta'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_text_footer($post, $post_meta)
  {
    ob_start();
    Helpers::load_view('footer', compact('post', 'post_meta'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_accordion($post, $post_meta)
  {
    ob_start();
    $agencies = $this->get_agencies($post->ID);
    Helpers::load_view('accordion', compact('post', 'post_meta', 'agencies'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_tools($post, $post_meta)
  {
    ob_start();
    Helpers::load_view('tools', compact('post', 'post_meta'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  private function get_agencies($post_id)
  {
    global $wpdb;
    $table_agencies = $wpdb->prefix . "cm_spreadsheets_agencies";
    $sql = "SELECT * FROM `$table_agencies` WHERE `post_id` = $post_id;";
    $result = $wpdb->get_results($sql, ARRAY_A);
    return $result;
  }


  private function getMonthAndYearByDate(string $date)
  {
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
    return strftime('%B/%Y', strtotime($date));
  }

  public function render_mean_comunication($post, $post_meta)
  {
    ob_start();
    $updated = $this->getMonthAndYearByDate($post->post_modified);
    if ($post_meta['cm_spreadsheet_type'] == 1) {
      $data = $this->get_data_mean_comunication($post->ID, true);
    } else {
      $data = $this->get_data_old_mean_comunication($post->ID, true);
    }
    Helpers::load_view('table-mean-comunication', compact('data', 'post_meta', 'updated', 'post'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_mean_region($post, $post_meta)
  {
    ob_start();
    if ($post_meta['cm_spreadsheet_type'] == 1) {
      $data = $this->get_data_mean_region($post->ID, true);
    } else {
      $data = $this->get_data_old_mean_region($post->ID);
    }
    Helpers::load_view('table-mean-region', compact('data', 'post_meta'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_region($post, $post_meta)
  {
    ob_start();
    if ($post_meta['cm_spreadsheet_type'] == 1) {
      $data = $this->get_data_region($post->ID, true);
    } else {
      $data = $this->get_data_old_region($post->ID);
    }
    Helpers::load_view('table-region', compact('post_meta', 'data', 'post'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_state($post, $post_meta)
  {
    ob_start();
    if ($post_meta['cm_spreadsheet_type'] == 1) {
      $data = $this->get_data_state($post->ID, true);
    } else {
      $data = $this->get_data_old_state($post->ID);
    }
    Helpers::load_view('table-state', compact('post', 'post_meta', 'data'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_ranking($post, $post_meta)
  {
    ob_start();
    $data_ranking = $this->get_data_ranking($post->ID);
    $data_ranking_uf = $this->get_data_ranking_uf($post->ID);
    Helpers::load_view('table-ranking', compact('data_ranking', 'data_ranking_uf', 'post', 'post_meta'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function get_data_ranking_uf($post_id)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets_ranking_state";
    $sql = "SELECT * FROM `$table_spreadsheet` WHERE `post_id` = $post_id;";
    $result = $wpdb->get_results($sql, ARRAY_A);
    return $result;
  }




  public function render_ranking_uf($post, $post_meta)
  {
    ob_start();
    $data_ranking = $this->get_data_ranking($post->ID);
    Helpers::load_view('table-ranking-uf', compact('data_ranking', 'post', 'post_meta'));
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }


  private function get_data_ranking($post_id)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets_ranking";
    $sql = "SELECT * FROM `$table_spreadsheet` WHERE `post_id` = $post_id;";
    $result = $wpdb->get_results($sql, ARRAY_A);
    return $result;
  }


  private function get_data_old_mean_comunication($post_id)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets_means";
    $sql = "SELECT * FROM `$table_spreadsheet` WHERE `post_id` = $post_id;";
    $result = $wpdb->get_results($sql, ARRAY_A);

    $sql = "SELECT COUNT(Id) AS total FROM `$table_spreadsheet`  WHERE `mean` IN ('MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV') AND `post_id` = $post_id";
    $meios = $wpdb->get_row($sql, ARRAY_A);

    $process_data = array(
      'cinema' => array(
        'real'      => $this->get_data_old($result, 'MIDIACN', 'real'),
        'dollar'    => $this->get_data_old($result, 'MIDIACN', 'dollar'),
        'share'     => $this->get_data_old($result, 'MIDIACN', 'share'),
      ),
      'internet' => array(
        'real'      => $this->get_data_old($result, 'MIDIAINT', 'real'),
        'dollar'    => $this->get_data_old($result, 'MIDIAINT', 'dollar'),
        'share'     => $this->get_data_old($result, 'MIDIAINT', 'share')
      ),
      'jornal' => array(
        'real'     => $this->get_data_old($result, 'MIDIAJR', 'real'),
        'dollar'   => $this->get_data_old($result, 'MIDIAJR', 'dollar'),
        'share'    => $this->get_data_old($result, 'MIDIAJR', 'share'),
      ),
      'midia_exterior' => array(
        'real'     => $this->get_data_old($result, 'MIDIAOU', 'real'),
        'dollar'  =>  $this->get_data_old($result, 'MIDIAOU', 'dollar'),
        'share'   =>  $this->get_data_old($result, 'MIDIAOU', 'share'),
      ),
      'radio' => array(
        'real'     => $this->get_data_old($result, 'MIDIARD', 'real'),
        'dollar'  =>  $this->get_data_old($result, 'MIDIARD', 'dollar'),
        'share'   =>  $this->get_data_old($result, 'MIDIARD', 'share'),
      ),
      'revista' => array(
        'real'     => $this->get_data_old($result, 'MIDIARV', 'real'),
        'dollar'  => $this->get_data_old($result, 'MIDIARV', 'dollar'),
        'share'   =>  $this->get_data_old($result, 'MIDIARV', 'share'),
      ),
      'tv_aberta' => array(
        'real'     => $this->get_data_old($result, 'MIDIATV', 'real'),
        'dollar'  =>  $this->get_data_old($result, 'MIDIATV', 'dollar'),
        'share'   =>  $this->get_data_old($result, 'MIDIATV', 'share')
      ),
      'tv_assinada' => array(
        'real'     => $this->get_data_old($result, 'MIDIATA', 'real'),
        'dollar'  =>  $this->get_data_old($result, 'MIDIATA', 'dollar'),
        'share'   =>  $this->get_data_old($result, 'MIDIATA', 'share'),
      ),
      'total' => array(
        'real' => $this->get_data_old($result, 'TOTAL', 'real'),
        'dollar' => $this->get_data_old($result, 'TOTAL', 'dollar'),
      )
    );

    if ($meios['total'] > 0) {
      $process_data['internet']['meios'] = array(
        'audio' => array(
          'real'     =>  $this->get_data_old($result, 'MIDIAIA', 'real'),
          'dollar'   =>  $this->get_data_old($result, 'MIDIAIA', 'dollar'),
          'share'    =>  $this->get_data_old($result, 'MIDIAIA', 'share'),
        ),
        'busca' => array(
          'real'     =>  $this->get_data_old($result, 'MIDIAIB', 'real'),
          'dollar'   =>  $this->get_data_old($result, 'MIDIAIB', 'dollar'),
          'share'    =>  $this->get_data_old($result, 'MIDIAIB', 'share'),
        ),
        'outros' => array(
          'real'     =>  $this->get_data_old($result, 'MIDIAID', 'real'),
          'dollar'   =>  $this->get_data_old($result, 'MIDIAID', 'dollar'),
          'share'    =>  $this->get_data_old($result, 'MIDIAID', 'share'),
        ),
        'social' => array(
          'real'     =>  $this->get_data_old($result, 'MIDIAIS', 'real'),
          'dollar'   =>  $this->get_data_old($result, 'MIDIAIS', 'dollar'),
          'share'    =>  $this->get_data_old($result, 'MIDIAIS', 'share'),
        ),
        'video' => array(
          'real'     => $this->get_data_old($result, 'MIDIAIV', 'real'),
          'dollar'   => $this->get_data_old($result, 'MIDIAIV', 'dollar'),
          'share'    => $this->get_data_old($result, 'MIDIAIV', 'share'),
        )
      );
    }

    return $process_data;
  }

  private function get_data_mean_comunication($post_id, $round = false)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets";
    $sql = "SELECT * FROM `$table_spreadsheet` WHERE `post_id` = $post_id;";
    $result = $wpdb->get_results($sql, ARRAY_A);

    $sql = "SELECT COUNT(Id) AS total FROM `$table_spreadsheet`  WHERE `mean` IN ('MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV') AND `post_id` = $post_id";
    $meios = $wpdb->get_row($sql, ARRAY_A);

    $process_data = array(
      'cinema' => array(
        'real'      => $this->process_data_mean_comunication($result, ['MIDIACN'], 'real', false, false, $round),
        'dollar'    => $this->process_data_mean_comunication($result, ['MIDIACN'], 'dollar', false, false, $round),
        'share'     => $this->process_data_mean_comunication($result, ['MIDIACN'], 'real', true, false, $round),
      ),
      'internet' => array(
        'real'     => $this->process_data_mean_comunication($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 'real', false, false, $round),
        'dollar'  => $this->process_data_mean_comunication($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 'dollar', false, false, $round),
        'share'   =>  $this->process_data_mean_comunication($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 'real', true, false, $round),
      ),
      'jornal' => array(
        'real'     => $this->process_data_mean_comunication($result, ['MIDIAJR'], 'real', false, false, $round),
        'dollar'  => $this->process_data_mean_comunication($result, ['MIDIAJR'], 'dollar', false, false, $round),
        'share'   => $this->process_data_mean_comunication($result, ['MIDIAJR'], 'real', true, false, $round),
      ),
      'midia_exterior' => array(
        'real'     => $this->process_data_mean_comunication($result, ['MIDIAOU'], 'real', false, false, $round),
        'dollar'  => $this->process_data_mean_comunication($result, ['MIDIAOU'], 'dollar', false, false, $round),
        'share'   =>  $this->process_data_mean_comunication($result, ['MIDIAOU'], 'real', true, false, $round),
      ),
      'radio' => array(
        'real'     => $this->process_data_mean_comunication($result, ['MIDIARD'], 'real', false, false, $round),
        'dollar'  => $this->process_data_mean_comunication($result, ['MIDIARD'], 'dollar', false, false, $round),
        'share'   =>  $this->process_data_mean_comunication($result, ['MIDIARD'], 'real', true, false, $round),
      ),
      'revista' => array(
        'real'     => $this->process_data_mean_comunication($result, ['MIDIARV'], 'real', false, false, $round),
        'dollar'  => $this->process_data_mean_comunication($result, ['MIDIARV'], 'dollar', false, false, $round),
        'share'   =>  $this->process_data_mean_comunication($result, ['MIDIARV'], 'real', true, false, $round),
      ),
      'tv_aberta' => array(
        'real'     => $this->process_data_mean_comunication($result, ['MIDIATV'], 'real', false, false, $round),
        'dollar'  => $this->process_data_mean_comunication($result, ['MIDIATV'], 'dollar', false, false, $round),
        'share'   =>  $this->process_data_mean_comunication($result, ['MIDIATV'], 'real', true, false, $round),
      ),
      'tv_assinada' => array(
        'real'     => $this->process_data_mean_comunication($result, ['MIDIATA'], 'real', false, false, $round),
        'dollar'  => $this->process_data_mean_comunication($result, ['MIDIATA'], 'dollar', false, false, $round),
        'share'   =>  $this->process_data_mean_comunication($result, ['MIDIATA'], 'real', true, false, $round),
      ),
      'total' => array(
        'real' => $this->process_data_mean_comunication($result, [], 'real', false, true, $round),
        'dollar' => $this->process_data_mean_comunication($result, [], 'dollar', false, true, $round),
      )
    );

    if ($meios['total'] > 0) {
      $process_data['internet']['meios'] = array(
        'audio' => array(
          'real'     => $this->process_data_mean_comunication($result, ['MIDIAIA'], 'real', false, false, $round),
          'dollar'  => $this->process_data_mean_comunication($result, ['MIDIAIA'], 'dollar', false, false, $round),
          'share'   =>  $this->process_share_mean_comunication($result, ['MIDIAIA'], 'real'),
        ),
        'busca' => array(
          'real'     => $this->process_data_mean_comunication($result, ['MIDIAIB'], 'real', false, false, $round),
          'dollar'  => $this->process_data_mean_comunication($result, ['MIDIAIB'], 'dollar', false, false, $round),
          'share'   =>  $this->process_share_mean_comunication($result, ['MIDIAIB'], 'real'),
        ),
        'outros' => array(
          'real'     => $this->process_data_mean_comunication($result, ['MIDIAID', 'MIDIAIN'], 'real', false, false, $round),
          'dollar'  => $this->process_data_mean_comunication($result, ['MIDIAID', 'MIDIAIN'], 'dollar', false, false, $round),
          'share'   =>  $this->process_share_mean_comunication($result, ['MIDIAID', 'MIDIAIN'], 'real'),
        ),
        'social' => array(
          'real'     => $this->process_data_mean_comunication($result, ['MIDIAIS'], 'real', false, false, $round),
          'dollar'  => $this->process_data_mean_comunication($result, ['MIDIAIS'], 'dollar', false, false, $round),
          'share'   =>  $this->process_share_mean_comunication($result, ['MIDIAIS'], 'real'),
        ),
        'video' => array(
          'real'     => $this->process_data_mean_comunication($result, ['MIDIAIV'], 'real', false, false, $round),
          'dollar'  => $this->process_data_mean_comunication($result, ['MIDIAIV'], 'dollar', false, false, $round),
          'share'   =>  $this->process_share_mean_comunication($result, ['MIDIAIV'], 'real'),
        )
      );
    }

    return $process_data;
  }

  private function get_data_old_region($post_id)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets_regions";
    $sql = "SELECT * FROM `$table_spreadsheet` WHERE post_id = $post_id";
    $result = $wpdb->get_results($sql, ARRAY_A);
    return array(
      'centro_oeste' => array(
        'real'    =>  $this->get_data_old($result, null, 'real', 'CO'),
        'dollar'  =>  $this->get_data_old($result, null, 'dollar', 'CO'),
        'share'   =>  $this->get_data_old($result, null, 'share', 'CO'),
      ),
      'nordeste' => array(
        'real'    =>  $this->get_data_old($result, null, 'real', 'NE'),
        'dollar'  =>  $this->get_data_old($result, null, 'dollar', 'NE'),
        'share'   =>  $this->get_data_old($result, null, 'share', 'NE'),
      ),
      'norte' => array(
        'real'    =>  $this->get_data_old($result, null, 'real', 'NO'),
        'dollar'  =>  $this->get_data_old($result, null, 'dollar', 'NO'),
        'share'   =>  $this->get_data_old($result, null, 'share', 'NO'),
      ),
      'sudeste' => array(
        'real'    =>  $this->get_data_old($result, null, 'real', 'SE'),
        'dollar'  =>  $this->get_data_old($result, null, 'dollar', 'SE'),
        'share'   =>  $this->get_data_old($result, null, 'share', 'SE'),
      ),
      'sul' => array(
        'real'    =>  $this->get_data_old($result, null, 'real', 'SU'),
        'dollar'  =>  $this->get_data_old($result, null, 'dollar', 'SU'),
        'share'   =>  $this->get_data_old($result, null, 'share', 'SU'),
      ),
      'merc_nascional' => array(
        'real'    =>  $this->get_data_old($result, null, 'real', 'MN'),
        'dollar'  =>  $this->get_data_old($result, null, 'dollar', 'MN'),
        'share'   =>  $this->get_data_old($result, null, 'share', 'MN'),
      ),
      'total' => array(
        'real'    =>  $this->get_data_old($result, null, 'real', 'TOTAL'),
        'dollar'  =>  $this->get_data_old($result, null, 'dollar', 'TOTAL'),
      )
    );
  }

  private function get_data_region($post_id, $round = false)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets";
    $table_states_region = $wpdb->prefix . "cm_states_region";
    $sql = "SELECT s.*, sr.region
    FROM `$table_spreadsheet` AS s
    LEFT JOIN `$table_states_region` AS sr ON (s.state = sr.state)
    WHERE s.post_id = $post_id";
    $result = $wpdb->get_results($sql, ARRAY_A);
    return array(
      'centro_oeste' => array(
        'real'    =>  $this->process_data_region($result, 'real', 3, false, false, $round),
        'dollar'  =>  $this->process_data_region($result, 'dollar', 3, false, false, $round),
        'share'   =>  $this->process_data_region($result, 'real', 3, true, false, $round),
      ),
      'nordeste' => array(
        'real'    =>  $this->process_data_region($result, 'real', 2, false, false, $round),
        'dollar'  =>  $this->process_data_region($result, 'dollar', 2, false, false, $round),
        'share'   =>  $this->process_data_region($result, 'real', 2, true, false, $round),
      ),
      'norte' => array(
        'real'    =>  $this->process_data_region($result, 'real', 1, false, false, $round),
        'dollar'  =>  $this->process_data_region($result, 'dollar', 1, false, false, $round),
        'share'   =>  $this->process_data_region($result, 'real', 1, true, false, $round),
      ),
      'sudeste' => array(
        'real'    =>  $this->process_data_region($result, 'real', 4, false, false, $round),
        'dollar'  =>  $this->process_data_region($result, 'dollar', 4, false, false, $round),
        'share'   =>  $this->process_data_region($result, 'real', 4, true, false, $round),
      ),
      'sul' => array(
        'real'    =>  $this->process_data_region($result, 'real', 5, false, false, $round),
        'dollar'  =>  $this->process_data_region($result, 'dollar', 5, false, false, $round),
        'share'   =>  $this->process_data_region($result, 'real', 5, true, false, $round),
      ),
      'merc_nascional' => array(
        'real'    =>  $this->process_data_region($result, 'real', null, false, false, $round),
        'dollar'  =>  $this->process_data_region($result, 'dollar', null, false, false, $round),
        'share'   =>  $this->process_data_region($result, 'real', null, true, false, $round),
      ),
      'total' => array(
        'real'    =>  $this->process_data_region($result, 'real', null, false, true, $round),
        'dollar'  =>  $this->process_data_region($result, 'dollar', null, false, true, $round),
      )
    );
  }

  private function get_data_old_mean_region($post_id)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets_means_regions";
    $sql = "SELECT * FROM `$table_spreadsheet` WHERE post_id = $post_id";
    $result = $wpdb->get_results($sql, ARRAY_A);

    $sql = "SELECT COUNT(Id) AS total FROM `$table_spreadsheet`  WHERE `mean` IN ('MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV') AND `post_id` = $post_id";
    $meios = $wpdb->get_row($sql, ARRAY_A);

    $process_data =  array(
      'cinema' => array(
        'centro_oeste' => array(
          'real' => $this->get_data_old($result, 'MIDIACN', 'real', 'CO'),
          'dollar' => $this->get_data_old($result, 'MIDIACN', 'dollar', 'CO'),
          'share' => $this->get_data_old($result, 'MIDIACN', 'share', 'CO'),
        ),
        'nordeste' => array(
          'real' => $this->get_data_old($result, 'MIDIACN', 'real', 'NE'),
          'dollar' => $this->get_data_old($result, 'MIDIACN', 'dollar', 'NE'),
          'share' => $this->get_data_old($result, 'MIDIACN', 'share', 'NE'),
        ),
        'norte' => array(
          'real' => $this->get_data_old($result, 'MIDIACN', 'real', 'NO'),
          'dollar' => $this->get_data_old($result, 'MIDIACN', 'dollar', 'NO'),
          'share' => $this->get_data_old($result, 'MIDIACN', 'share', 'NO'),
        ),
        'sudeste' => array(
          'real' => $this->get_data_old($result, 'MIDIACN', 'real', 'SE'),
          'dollar' => $this->get_data_old($result, 'MIDIACN', 'dollar', 'SE'),
          'share' => $this->get_data_old($result, 'MIDIACN', 'share', 'SE'),
        ),
        'sul' => array(
          'real' => $this->get_data_old($result, 'MIDIACN', 'real', 'SU'),
          'dollar' => $this->get_data_old($result, 'MIDIACN', 'dollar', 'SU'),
          'share' => $this->get_data_old($result, 'MIDIACN', 'share', 'SU'),
        ),
        'mer_nacional' => array(
          'real' => $this->get_data_old($result, 'MIDIACN', 'real', 'MN'),
          'dollar' => $this->get_data_old($result, 'MIDIACN', 'dollar', 'MN'),
          'share' => $this->get_data_old($result, 'MIDIACN', 'share', 'MN'),
        ),
      ),
      'internet' => array(
        'centro_oeste' => array(
          'real' => $this->get_data_old($result, 'MIDIAINT', 'real', 'CO'),
          'dollar' => $this->get_data_old($result, 'MIDIAINT', 'dollar', 'CO'),
          'share' => $this->get_data_old($result, 'MIDIAINT', 'share', 'CO'),
        ),
        'nordeste' => array(
          'real' => $this->get_data_old($result, 'MIDIAINT', 'real', 'NE'),
          'dollar' => $this->get_data_old($result, 'MIDIAINT', 'dollar', 'NE'),
          'share' => $this->get_data_old($result, 'MIDIAINT', 'share', 'NE'),
        ),
        'norte' => array(
          'real' => $this->get_data_old($result, 'MIDIAINT', 'real', 'NO'),
          'dollar' => $this->get_data_old($result, 'MIDIAINT', 'dollar', 'NO'),
          'share' => $this->get_data_old($result, 'MIDIAINT', 'share', 'NO'),
        ),
        'sudeste' => array(
          'real' => $this->get_data_old($result, 'MIDIAINT', 'real', 'SE'),
          'dollar' => $this->get_data_old($result, 'MIDIAINT', 'dollar', 'SE'),
          'share' => $this->get_data_old($result, 'MIDIAINT', 'share', 'SE'),
        ),
        'sul' => array(
          'real' => $this->get_data_old($result, 'MIDIAINT', 'real', 'SU'),
          'dollar' => $this->get_data_old($result, 'MIDIAINT', 'dollar', 'SU'),
          'share' => $this->get_data_old($result, 'MIDIAINT', 'share', 'SU'),
        ),
        'mer_nacional' => array(
          'real' => $this->get_data_old($result, 'MIDIAINT', 'real', 'MN'),
          'dollar' => $this->get_data_old($result, 'MIDIAINT', 'dollar', 'MN'),
          'share' => $this->get_data_old($result, 'MIDIAINT', 'share', 'MN'),
        ),
      ),
      'jornal' => array(
        'centro_oeste' => array(
          'real' => $this->get_data_old($result, 'MIDIAJR', 'real', 'CO'),
          'dollar' => $this->get_data_old($result, 'MIDIAJR', 'dollar', 'CO'),
          'share' => $this->get_data_old($result, 'MIDIAJR', 'share', 'CO'),
        ),
        'nordeste' => array(
          'real' => $this->get_data_old($result, 'MIDIAJR', 'real', 'NE'),
          'dollar' => $this->get_data_old($result, 'MIDIAJR', 'dollar', 'NE'),
          'share' => $this->get_data_old($result, 'MIDIAJR', 'share', 'NE'),
        ),
        'norte' => array(
          'real' => $this->get_data_old($result, 'MIDIAJR', 'real', 'NO'),
          'dollar' => $this->get_data_old($result, 'MIDIAJR', 'dollar', 'NO'),
          'share' => $this->get_data_old($result, 'MIDIAJR', 'share', 'NO'),
        ),
        'sudeste' => array(
          'real' => $this->get_data_old($result, 'MIDIAJR', 'real', 'SE'),
          'dollar' => $this->get_data_old($result, 'MIDIAJR', 'dollar', 'SE'),
          'share' => $this->get_data_old($result, 'MIDIAJR', 'share', 'SE'),
        ),
        'sul' => array(
          'real' => $this->get_data_old($result, 'MIDIAJR', 'real', 'SU'),
          'dollar' => $this->get_data_old($result, 'MIDIAJR', 'dollar', 'SU'),
          'share' => $this->get_data_old($result, 'MIDIAJR', 'share', 'SU'),
        ),
        'mer_nacional' => array(
          'real' => $this->get_data_old($result, 'MIDIAJR', 'real', 'MN'),
          'dollar' => $this->get_data_old($result, 'MIDIAJR', 'dollar', 'MN'),
          'share' => $this->get_data_old($result, 'MIDIAJR', 'share', 'MN'),
        ),
      ),
      'midia_exterior' => array(
        'centro_oeste' => array(
          'real' => $this->get_data_old($result, 'MIDIAOU', 'real', 'CO'),
          'dollar' => $this->get_data_old($result, 'MIDIAOU', 'dollar', 'CO'),
          'share' => $this->get_data_old($result, 'MIDIAOU', 'share', 'CO'),
        ),
        'nordeste' => array(
          'real' => $this->get_data_old($result, 'MIDIAOU', 'real', 'NE'),
          'dollar' => $this->get_data_old($result, 'MIDIAOU', 'dollar', 'NE'),
          'share' => $this->get_data_old($result, 'MIDIAOU', 'share', 'NE'),
        ),
        'norte' => array(
          'real' => $this->get_data_old($result, 'MIDIAOU', 'real', 'NO'),
          'dollar' => $this->get_data_old($result, 'MIDIAOU', 'dollar', 'NO'),
          'share' => $this->get_data_old($result, 'MIDIAOU', 'share', 'NO'),
        ),
        'sudeste' => array(
          'real' => $this->get_data_old($result, 'MIDIAOU', 'real', 'SE'),
          'dollar' => $this->get_data_old($result, 'MIDIAOU', 'dollar', 'SE'),
          'share' => $this->get_data_old($result, 'MIDIAOU', 'share', 'SE'),
        ),
        'sul' => array(
          'real' => $this->get_data_old($result, 'MIDIAOU', 'real', 'SU'),
          'dollar' => $this->get_data_old($result, 'MIDIAOU', 'dollar', 'SU'),
          'share' => $this->get_data_old($result, 'MIDIAOU', 'share', 'SU'),
        ),
        'mer_nacional' => array(
          'real' => $this->get_data_old($result, 'MIDIAOU', 'real', 'MN'),
          'dollar' => $this->get_data_old($result, 'MIDIAOU', 'dollar', 'MN'),
          'share' => $this->get_data_old($result, 'MIDIAOU', 'share', 'MN'),
        ),
      ),
      'radio' => array(
        'centro_oeste' => array(
          'real' => $this->get_data_old($result, 'MIDIARD', 'real', 'CO'),
          'dollar' => $this->get_data_old($result, 'MIDIARD', 'dollar', 'CO'),
          'share' => $this->get_data_old($result, 'MIDIARD', 'share', 'CO'),
        ),
        'nordeste' => array(
          'real' => $this->get_data_old($result, 'MIDIARD', 'real', 'NE'),
          'dollar' => $this->get_data_old($result, 'MIDIARD', 'dollar', 'NE'),
          'share' => $this->get_data_old($result, 'MIDIARD', 'share', 'NE'),
        ),
        'norte' => array(
          'real' => $this->get_data_old($result, 'MIDIARD', 'real', 'NO'),
          'dollar' => $this->get_data_old($result, 'MIDIARD', 'dollar', 'NO'),
          'share' => $this->get_data_old($result, 'MIDIARD', 'share', 'NO'),
        ),
        'sudeste' => array(
          'real' => $this->get_data_old($result, 'MIDIARD', 'real', 'SE'),
          'dollar' => $this->get_data_old($result, 'MIDIARD', 'dollar', 'SE'),
          'share' => $this->get_data_old($result, 'MIDIARD', 'share', 'SE'),
        ),
        'sul' => array(
          'real' => $this->get_data_old($result, 'MIDIARD', 'real', 'SU'),
          'dollar' => $this->get_data_old($result, 'MIDIARD', 'dollar', 'SU'),
          'share' => $this->get_data_old($result, 'MIDIARD', 'share', 'SU'),
        ),
        'mer_nacional' => array(
          'real' => $this->get_data_old($result, 'MIDIARD', 'real', 'MN'),
          'dollar' => $this->get_data_old($result, 'MIDIARD', 'dollar', 'MN'),
          'share' => $this->get_data_old($result, 'MIDIARD', 'share', 'MN'),
        ),
      ),
      'revista' => array(
        'centro_oeste' => array(
          'real' => $this->get_data_old($result, 'MIDIARV', 'real', 'CO'),
          'dollar' => $this->get_data_old($result, 'MIDIARV', 'dollar', 'CO'),
          'share' => $this->get_data_old($result, 'MIDIARV', 'share', 'CO'),
        ),
        'nordeste' => array(
          'real' => $this->get_data_old($result, 'MIDIARV', 'real', 'NE'),
          'dollar' => $this->get_data_old($result, 'MIDIARV', 'dollar', 'NE'),
          'share' => $this->get_data_old($result, 'MIDIARV', 'share', 'NE'),
        ),
        'norte' => array(
          'real' => $this->get_data_old($result, 'MIDIARV', 'real', 'NO'),
          'dollar' => $this->get_data_old($result, 'MIDIARV', 'dollar', 'NO'),
          'share' => $this->get_data_old($result, 'MIDIARV', 'share', 'NO'),
        ),
        'sudeste' => array(
          'real' => $this->get_data_old($result, 'MIDIARV', 'real', 'SE'),
          'dollar' => $this->get_data_old($result, 'MIDIARV', 'dollar', 'SE'),
          'share' => $this->get_data_old($result, 'MIDIARV', 'share', 'SE'),
        ),
        'sul' => array(
          'real' => $this->get_data_old($result, 'MIDIARV', 'real', 'SU'),
          'dollar' => $this->get_data_old($result, 'MIDIARV', 'dollar', 'SU'),
          'share' => $this->get_data_old($result, 'MIDIARV', 'share', 'SU'),
        ),
        'mer_nacional' => array(
          'real' => $this->get_data_old($result, 'MIDIARV', 'real', 'MN'),
          'dollar' => $this->get_data_old($result, 'MIDIARV', 'dollar', 'MN'),
          'share' => $this->get_data_old($result, 'MIDIARV', 'share', 'MN'),
        ),
      ),
      'tv_aberta' => array(
        'centro_oeste' => array(
          'real' => $this->get_data_old($result, 'MIDIATV', 'real', 'CO'),
          'dollar' => $this->get_data_old($result, 'MIDIATV', 'dollar', 'CO'),
          'share' => $this->get_data_old($result, 'MIDIATV', 'share', 'CO'),
        ),
        'nordeste' => array(
          'real' => $this->get_data_old($result, 'MIDIATV', 'real', 'NE'),
          'dollar' => $this->get_data_old($result, 'MIDIATV', 'dollar', 'NE'),
          'share' => $this->get_data_old($result, 'MIDIATV', 'share', 'NE'),
        ),
        'norte' => array(
          'real' => $this->get_data_old($result, 'MIDIATV', 'real', 'NO'),
          'dollar' => $this->get_data_old($result, 'MIDIATV', 'dollar', 'NO'),
          'share' => $this->get_data_old($result, 'MIDIATV', 'share', 'NO'),
        ),
        'sudeste' => array(
          'real' => $this->get_data_old($result, 'MIDIATV', 'real', 'SE'),
          'dollar' => $this->get_data_old($result, 'MIDIATV', 'dollar', 'SE'),
          'share' => $this->get_data_old($result, 'MIDIATV', 'share', 'SE'),
        ),
        'sul' => array(
          'real' => $this->get_data_old($result, 'MIDIATV', 'real', 'SU'),
          'dollar' => $this->get_data_old($result, 'MIDIATV', 'dollar', 'SU'),
          'share' => $this->get_data_old($result, 'MIDIATV', 'share', 'SU'),
        ),
        'mer_nacional' => array(
          'real' => $this->get_data_old($result, 'MIDIATV', 'real', 'MN'),
          'dollar' => $this->get_data_old($result, 'MIDIATV', 'dollar', 'MN'),
          'share' => $this->get_data_old($result, 'MIDIATV', 'share', 'MN'),
        ),
      ),
      'tv_assinada' => array(
        'centro_oeste' => array(
          'real' => $this->get_data_old($result, 'MIDIATA', 'real', 'CO'),
          'dollar' => $this->get_data_old($result, 'MIDIATA', 'dollar', 'CO'),
          'share' => $this->get_data_old($result, 'MIDIATA', 'share', 'CO'),
        ),
        'nordeste' => array(
          'real' => $this->get_data_old($result, 'MIDIATA', 'real', 'NE'),
          'dollar' => $this->get_data_old($result, 'MIDIATA', 'dollar', 'NE'),
          'share' => $this->get_data_old($result, 'MIDIATA', 'share', 'NE'),
        ),
        'norte' => array(
          'real' => $this->get_data_old($result, 'MIDIATA', 'real', 'NO'),
          'dollar' => $this->get_data_old($result, 'MIDIATA', 'dollar', 'NO'),
          'share' => $this->get_data_old($result, 'MIDIATA', 'share', 'NO'),
        ),
        'sudeste' => array(
          'real' => $this->get_data_old($result, 'MIDIATA', 'real', 'SE'),
          'dollar' => $this->get_data_old($result, 'MIDIATA', 'dollar', 'SE'),
          'share' => $this->get_data_old($result, 'MIDIATA', 'share', 'SE'),
        ),
        'sul' => array(
          'real' => $this->get_data_old($result, 'MIDIATA', 'real', 'SU'),
          'dollar' => $this->get_data_old($result, 'MIDIATA', 'dollar', 'SU'),
          'share' => $this->get_data_old($result, 'MIDIATA', 'share', 'SU'),
        ),
        'mer_nacional' => array(
          'real' => $this->get_data_old($result, 'MIDIATA', 'real', 'MN'),
          'dollar' => $this->get_data_old($result, 'MIDIATA', 'dollar', 'MN'),
          'share' => $this->get_data_old($result, 'MIDIATA', 'share', 'MN'),
        ),
      ),
      'total' => array(
        'centro_oeste' => array(
          'real' => $this->get_data_old($result, 'TOTAL', 'real', 'CO'),
          'dollar' => $this->get_data_old($result, 'TOTAL', 'dollar', 'CO'),
        ),
        'nordeste' => array(
          'real' => $this->get_data_old($result, 'TOTAL', 'real', 'NE'),
          'dollar' => $this->get_data_old($result, 'TOTAL', 'dollar', 'NE'),
        ),
        'norte' => array(
          'real' => $this->get_data_old($result, 'TOTAL', 'real', 'NO'),
          'dollar' => $this->get_data_old($result, 'TOTAL', 'dollar', 'NO'),
        ),
        'sudeste' => array(
          'real' => $this->get_data_old($result, 'TOTAL', 'real', 'SE'),
          'dollar' => $this->get_data_old($result, 'TOTAL', 'dollar', 'SE'),
        ),
        'sul' => array(
          'real' => $this->get_data_old($result, 'TOTAL', 'real', 'SU'),
          'dollar' => $this->get_data_old($result, 'TOTAL', 'dollar', 'SU'),
        ),
        'mer_nacional' => array(
          'real' => $this->get_data_old($result, 'TOTAL', 'real', 'MN'),
          'dollar' => $this->get_data_old($result, 'TOTAL', 'dollar', 'MN'),
        ),
      ),
    );

    if ($meios['total'] > 0) {
      $process_data['internet']['meios'] = array(
        'audio' => array(
          'centro_oeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIA', 'real', 'CO'),
            'dollar' => $this->get_data_old($result, 'MIDIAIA', 'dollar', 'CO'),
            'share' => $this->get_data_old($result, 'MIDIAIA', 'share', 'CO'),
          ),
          'nordeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIA', 'real', 'NE'),
            'dollar' => $this->get_data_old($result, 'MIDIAIA', 'dollar', 'NE'),
            'share' => $this->get_data_old($result, 'MIDIAIA', 'share', 'NE'),
          ),
          'norte' => array(
            'real' => $this->get_data_old($result, 'MIDIAIA', 'real', 'NO'),
            'dollar' => $this->get_data_old($result, 'MIDIAIA', 'dollar', 'NO'),
            'share' => $this->get_data_old($result, 'MIDIAIA', 'share', 'NO'),
          ),
          'sudeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIA', 'real', 'SE'),
            'dollar' => $this->get_data_old($result, 'MIDIAIA', 'dollar', 'SE'),
            'share' => $this->get_data_old($result, 'MIDIAIA', 'share', 'SE'),
          ),
          'sul' => array(
            'real' => $this->get_data_old($result, 'MIDIAIA', 'real', 'SU'),
            'dollar' => $this->get_data_old($result, 'MIDIAIA', 'dollar', 'SU'),
            'share' => $this->get_data_old($result, 'MIDIAIA', 'share', 'SU'),
          ),
          'mer_nacional' => array(
            'real' => $this->get_data_old($result, 'MIDIAIA', 'real', 'MN'),
            'dollar' => $this->get_data_old($result, 'MIDIAIA', 'dollar', 'MN'),
            'share' => $this->get_data_old($result, 'MIDIAIA', 'share', 'MN'),
          ),
        ),
        'busca' => array(
          'centro_oeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIB', 'real', 'CO'),
            'dollar' => $this->get_data_old($result, 'MIDIAIB', 'dollar', 'CO'),
            'share' => $this->get_data_old($result, 'MIDIAIB', 'share', 'CO'),
          ),
          'nordeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIB', 'real', 'NE'),
            'dollar' => $this->get_data_old($result, 'MIDIAIB', 'dollar', 'NE'),
            'share' => $this->get_data_old($result, 'MIDIAIB', 'share', 'NE'),
          ),
          'norte' => array(
            'real' => $this->get_data_old($result, 'MIDIAIB', 'real', 'NO'),
            'dollar' => $this->get_data_old($result, 'MIDIAIB', 'dollar', 'NO'),
            'share' => $this->get_data_old($result, 'MIDIAIB', 'share', 'NO'),
          ),
          'sudeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIB', 'real', 'SE'),
            'dollar' => $this->get_data_old($result, 'MIDIAIB', 'dollar', 'SE'),
            'share' => $this->get_data_old($result, 'MIDIAIB', 'share', 'SE'),
          ),
          'sul' => array(
            'real' => $this->get_data_old($result, 'MIDIAIB', 'real', 'SU'),
            'dollar' => $this->get_data_old($result, 'MIDIAIB', 'dollar', 'SU'),
            'share' => $this->get_data_old($result, 'MIDIAIB', 'share', 'SU'),
          ),
          'mer_nacional' => array(
            'real' => $this->get_data_old($result, 'MIDIAIB', 'real', 'MN'),
            'dollar' => $this->get_data_old($result, 'MIDIAIB', 'dollar', 'MN'),
            'share' => $this->get_data_old($result, 'MIDIAIB', 'share', 'MN'),
          ),
        ),
        'outros' => array(
          'centro_oeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAID', 'real', 'CO'),
            'dollar' => $this->get_data_old($result, 'MIDIAID', 'dollar', 'CO'),
            'share' => $this->get_data_old($result, 'MIDIAID', 'share', 'CO'),
          ),
          'nordeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAID', 'real', 'NE'),
            'dollar' => $this->get_data_old($result, 'MIDIAID', 'dollar', 'NE'),
            'share' => $this->get_data_old($result, 'MIDIAID', 'share', 'NE'),
          ),
          'norte' => array(
            'real' => $this->get_data_old($result, 'MIDIAID', 'real', 'NO'),
            'dollar' => $this->get_data_old($result, 'MIDIAID', 'dollar', 'NO'),
            'share' => $this->get_data_old($result, 'MIDIAID', 'share', 'NO'),
          ),
          'sudeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAID', 'real', 'SE'),
            'dollar' => $this->get_data_old($result, 'MIDIAID', 'dollar', 'SE'),
            'share' => $this->get_data_old($result, 'MIDIAID', 'share', 'SE'),
          ),
          'sul' => array(
            'real' => $this->get_data_old($result, 'MIDIAID', 'real', 'SU'),
            'dollar' => $this->get_data_old($result, 'MIDIAID', 'dollar', 'SU'),
            'share' => $this->get_data_old($result, 'MIDIAID', 'share', 'SU'),
          ),
          'mer_nacional' => array(
            'real' => $this->get_data_old($result, 'MIDIAID', 'real', 'MN'),
            'dollar' => $this->get_data_old($result, 'MIDIAID', 'dollar', 'MN'),
            'share' => $this->get_data_old($result, 'MIDIAID', 'share', 'MN'),
          ),
        ),
        'social' => array(
          'centro_oeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIS', 'real', 'CO'),
            'dollar' => $this->get_data_old($result, 'MIDIAIS', 'dollar', 'CO'),
            'share' => $this->get_data_old($result, 'MIDIAIS', 'share', 'CO'),
          ),
          'nordeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIS', 'real', 'NE'),
            'dollar' => $this->get_data_old($result, 'MIDIAIS', 'dollar', 'NE'),
            'share' => $this->get_data_old($result, 'MIDIAIS', 'share', 'NE'),
          ),
          'norte' => array(
            'real' => $this->get_data_old($result, 'MIDIAIS', 'real', 'NO'),
            'dollar' => $this->get_data_old($result, 'MIDIAIS', 'dollar', 'NO'),
            'share' => $this->get_data_old($result, 'MIDIAIS', 'share', 'NO'),
          ),
          'sudeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIS', 'real', 'SE'),
            'dollar' => $this->get_data_old($result, 'MIDIAIS', 'dollar', 'SE'),
            'share' => $this->get_data_old($result, 'MIDIAIS', 'share', 'SE'),
          ),
          'sul' => array(
            'real' => $this->get_data_old($result, 'MIDIAIS', 'real', 'SU'),
            'dollar' => $this->get_data_old($result, 'MIDIAIS', 'dollar', 'SU'),
            'share' => $this->get_data_old($result, 'MIDIAIS', 'share', 'SU'),
          ),
          'mer_nacional' => array(
            'real' => $this->get_data_old($result, 'MIDIAIS', 'real', 'MN'),
            'dollar' => $this->get_data_old($result, 'MIDIAIS', 'dollar', 'MN'),
            'share' => $this->get_data_old($result, 'MIDIAIS', 'share', 'MN'),
          ),
        ),
        'video' => array(
          'centro_oeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIV', 'real', 'CO'),
            'dollar' => $this->get_data_old($result, 'MIDIAIV', 'dollar', 'CO'),
            'share' => $this->get_data_old($result, 'MIDIAIV', 'share', 'CO'),
          ),
          'nordeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIV', 'real', 'NE'),
            'dollar' => $this->get_data_old($result, 'MIDIAIV', 'dollar', 'NE'),
            'share' => $this->get_data_old($result, 'MIDIAIV', 'share', 'NE'),
          ),
          'norte' => array(
            'real' => $this->get_data_old($result, 'MIDIAIV', 'real', 'NO'),
            'dollar' => $this->get_data_old($result, 'MIDIAIV', 'dollar', 'NO'),
            'share' => $this->get_data_old($result, 'MIDIAIV', 'share', 'NO'),
          ),
          'sudeste' => array(
            'real' => $this->get_data_old($result, 'MIDIAIV', 'real', 'SE'),
            'dollar' => $this->get_data_old($result, 'MIDIAIV', 'dollar', 'SE'),
            'share' => $this->get_data_old($result, 'MIDIAIV', 'share', 'SE'),
          ),
          'sul' => array(
            'real' => $this->get_data_old($result, 'MIDIAIV', 'real', 'SU'),
            'dollar' => $this->get_data_old($result, 'MIDIAIV', 'dollar', 'SU'),
            'share' => $this->get_data_old($result, 'MIDIAIV', 'share', 'SU'),
          ),
          'mer_nacional' => array(
            'real' => $this->get_data_old($result, 'MIDIAIV', 'real', 'MN'),
            'dollar' => $this->get_data_old($result, 'MIDIAIV', 'dollar', 'MN'),
            'share' => $this->get_data_old($result, 'MIDIAIV', 'share', 'MN'),
          ),
        ),
      );
    }

    return $process_data;
  }

  private function get_data_mean_region($post_id, $round = false)
  {
    /*
    NORTE - 1
    NORDESTE - 2
    CENTRO OESTE - 3
    SUDESTE - 4
    SUL - 5
    */
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets";
    $table_states_region = $wpdb->prefix . "cm_states_region";
    $sql = "SELECT s.*, sr.region
    FROM `$table_spreadsheet` AS s
    LEFT JOIN `$table_states_region` AS sr ON (s.state = sr.state)
    WHERE s.post_id = $post_id";
    $result = $wpdb->get_results($sql, ARRAY_A);

    $sql = "SELECT COUNT(Id) AS total FROM `$table_spreadsheet`  WHERE `mean` IN ('MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV') AND `post_id` = $post_id";
    $meios = $wpdb->get_row($sql, ARRAY_A);

    $process_data =  array(
      'cinema' => array(
        'centro_oeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIACN'], 3, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIACN'], 3, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIACN'], 3, 'real', false, $round, true)
        ),
        'nordeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIACN'], 2, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIACN'], 2, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIACN'], 2, 'real', false, $round, true)
        ),
        'norte' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIACN'], 1, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIACN'], 1, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIACN'], 1, 'real', false, $round, true)
        ),
        'sudeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIACN'], 4, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIACN'], 4, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIACN'], 4, 'real', false, $round, true)
        ),
        'sul' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIACN'], 5, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIACN'], 5, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIACN'], 5, 'real', false, $round, true)
        ),
        'mer_nacional' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIACN'], null, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIACN'], null, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIACN'], null, 'real', false, $round, true)
        ),
      ),
      'internet' => array(
        'centro_oeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 3, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 3, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 3, 'real', false, $round, true)
        ),
        'nordeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 2, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 2, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 2, 'real', false, $round, true)
        ),
        'norte' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 1, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 1, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 1, 'real', false, $round, true)
        ),
        'sudeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 4, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 4, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 4, 'real', false, $round, true)
        ),
        'sul' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 5, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 5, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 5, 'real', false, $round, true)
        ),
        'mer_nacional' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], null, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], null, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], null, 'real', false, $round, true)
        )
      ),
      'jornal' => array(
        'centro_oeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAJR'], 3, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAJR'], 3, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAJR'], 3, 'real', false, $round, true)
        ),
        'nordeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAJR'], 2, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAJR'], 2, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAJR'], 2, 'real', false, $round, true)
        ),
        'norte' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAJR'], 1, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAJR'], 1, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAJR'], 1, 'real', false, $round, true)
        ),
        'sudeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAJR'], 4, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAJR'], 4, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAJR'], 4, 'real', false, $round, true)
        ),
        'sul' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAJR'], 5, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAJR'], 5, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAJR'], 5, 'real', false, $round, true)
        ),
        'mer_nacional' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAJR'], null, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAJR'], null, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAJR'], null, 'real', false, $round, true)
        ),
      ),
      'midia_exterior' => array(
        'centro_oeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAOU'], 3, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAOU'], 3, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAOU'], 3, 'real', false, $round, true)
        ),
        'nordeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAOU'], 2, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAOU'], 2, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAOU'], 2, 'real', false, $round, true)
        ),
        'norte' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAOU'], 1, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAOU'], 1, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAOU'], 1, 'real', false, $round, true)
        ),
        'sudeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAOU'], 4, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAOU'], 4, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAOU'], 4, 'real', false, $round, true)
        ),
        'sul' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAOU'], 5, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAOU'], 5, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAOU'], 5, 'real', false, $round, true)
        ),
        'mer_nacional' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIAOU'], null, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIAOU'], null, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIAOU'], null, 'real', false, $round, true)
        ),
      ),
      'radio' => array(
        'centro_oeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARD'], 3, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARD'], 3, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARD'], 3, 'real', false, $round, true)
        ),
        'nordeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARD'], 2, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARD'], 2, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARD'], 2, 'real', false, $round, true)
        ),
        'norte' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARD'], 1, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARD'], 1, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARD'], 1, 'real', false, $round, true)
        ),
        'sudeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARD'], 4, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARD'], 4, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARD'], 4, 'real', false, $round, true)
        ),
        'sul' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARD'], 5, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARD'], 5, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARD'], 5, 'real', false, $round, true)
        ),
        'mer_nacional' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARD'], null, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARD'], null, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARD'], null, 'real', false, $round, true)
        ),
      ),
      'revista' => array(
        'centro_oeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARV'], 3, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARV'], 3, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARV'], 3, 'real', false, $round, true)
        ),
        'nordeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARV'], 2, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARV'], 2, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARV'], 2, 'real', false, $round, true)
        ),
        'norte' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARV'], 1, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARV'], 1, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARV'], 1, 'real', false, $round, true)
        ),
        'sudeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARV'], 4, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARV'], 4, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARV'], 4, 'real', false, $round, true)
        ),
        'sul' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARV'], 5, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARV'], 5, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARV'], 5, 'real', false, $round, true)
        ),
        'mer_nacional' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIARV'], null, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIARV'], null, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIARV'], null, 'real', false, $round, true)
        ),
      ),
      'tv_aberta' => array(
        'centro_oeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATV'], 3, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATV'], 3, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATV'], 3, 'real', false, $round, true)
        ),
        'nordeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATV'], 2, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATV'], 2, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATV'], 2, 'real', false, $round, true)
        ),
        'norte' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATV'], 1, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATV'], 1, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATV'], 1, 'real', false, $round, true)
        ),
        'sudeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATV'], 4, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATV'], 4, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATV'], 4, 'real', false, $round, true)
        ),
        'sul' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATV'], 5, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATV'], 5, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATV'], 5, 'real', false, $round, true)
        ),
        'mer_nacional' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATV'], null, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATV'], null, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATV'], null, 'real', false, $round, true)
        ),
      ),
      'tv_assinada' => array(
        'centro_oeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATA'], 3, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATA'], 3, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATA'], 3, 'real', false, $round, true)
        ),
        'nordeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATA'], 2, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATA'], 2, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATA'], 2, 'real', false, $round, true)
        ),
        'norte' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATA'], 1, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATA'], 1, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATA'], 1, 'real', false, $round, true)
        ),
        'sudeste' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATA'], 4, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATA'], 4, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATA'], 4, 'real', false, $round, true)
        ),
        'sul' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATA'], 5, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATA'], 5, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATA'], 5, 'real', false, $round, true)
        ),
        'mer_nacional' => array(
          'real' => $this->process_data_mean_region($result, ['MIDIATA'], null, 'real', false, $round),
          'dollar' => $this->process_data_mean_region($result, ['MIDIATA'], null, 'dollar', false, $round),
          'share' => $this->process_data_mean_region($result, ['MIDIATA'], null, 'real', false, $round, true)
        ),
      ),
      'total' => array(
        'centro_oeste' => array(
          'real' => $this->process_data_mean_region($result, [], 3, 'real', true, $round),
          'dollar' => $this->process_data_mean_region($result, [], 3, 'dollar', true, $round)
        ),
        'nordeste' => array(
          'real' => $this->process_data_mean_region($result, [], 2, 'real', true, $round),
          'dollar' => $this->process_data_mean_region($result, [], 2, 'dollar', true, $round)
        ),
        'norte' => array(
          'real' => $this->process_data_mean_region($result, [], 1, 'real', true, $round),
          'dollar' => $this->process_data_mean_region($result, [], 1, 'dollar', true, $round)
        ),
        'sudeste' => array(
          'real' => $this->process_data_mean_region($result, [], 4, 'real', true, $round),
          'dollar' => $this->process_data_mean_region($result, [], 4, 'dollar', true, $round)
        ),
        'sul' => array(
          'real' => $this->process_data_mean_region($result, [], 5, 'real', true, $round),
          'dollar' => $this->process_data_mean_region($result, [], 5, 'dollar', true, $round)
        ),
        'mer_nacional' => array(
          'real' => $this->process_data_mean_region($result, [], null, 'real', true, $round),
          'dollar' => $this->process_data_mean_region($result, [], null, 'dollar', true, $round)
        ),
      ),
    );

    if ($meios['total'] > 0) {
      $process_data['internet']['meios'] = array(
        'audio' => array(
          'centro_oeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIA'], 3, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIA'], 3, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIA'], 3, 'real', false, $round, true)
          ),
          'nordeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIA'], 2, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIA'], 2, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIA'], 2, 'real', false, $round, true)
          ),
          'norte' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIA'], 1, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIA'], 1, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIA'], 1, 'real', false, $round, true)
          ),
          'sudeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIA'], 4, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIA'], 4, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIA'], 4, 'real', false, $round, true)
          ),
          'sul' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIA'], 5, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIA'], 5, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIA'], 5, 'real', false, $round, true)
          ),
          'mer_nacional' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIA'], null, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIA'], null, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIA'], null, 'real', false, $round, true)
          )
        ),
        'busca' => array(
          'centro_oeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIB'], 3, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIB'], 3, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIB'], 3, 'real', false, $round, true)
          ),
          'nordeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIB'], 2, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIB'], 2, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIB'], 2, 'real', false, $round, true)
          ),
          'norte' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIB'], 1, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIB'], 1, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIB'], 1, 'real', false, $round, true)
          ),
          'sudeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIB'], 4, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIB'], 4, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIB'], 4, 'real', false, $round, true)
          ),
          'sul' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIB'], 5, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIB'], 5, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIB'], 5, 'real', false, $round, true)
          ),
          'mer_nacional' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIB'], null, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIB'], null, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIB'], null, 'real', false, $round, true)
          )
        ),
        'outros' => array(
          'centro_oeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 3, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 3, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 3, 'real', false, $round, true)
          ),
          'nordeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 2, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 2, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 2, 'real', false, $round, true)
          ),
          'norte' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 1, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 1, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 1, 'real', false, $round, true)
          ),
          'sudeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 4, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 4, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 4, 'real', false, $round, true)
          ),
          'sul' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 5, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 5, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], 5, 'real', false, $round, true)
          ),
          'mer_nacional' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], null, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], null, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAID', 'MIDIAIN'], null, 'real', false, $round, true)
          )
        ),
        'social' => array(
          'centro_oeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIS'], 3, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIS'], 3, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIS'], 3, 'real', false, $round, true)
          ),
          'nordeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIS'], 2, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIS'], 2, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIS'], 2, 'real', false, $round, true)
          ),
          'norte' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIS'], 1, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIS'], 1, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIS'], 1, 'real', false, $round, true)
          ),
          'sudeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIS'], 4, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIS'], 4, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIS'], 4, 'real', false, $round, true)
          ),
          'sul' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIS'], 5, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIS'], 5, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIS'], 5, 'real', false, $round, true)
          ),
          'mer_nacional' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIS'], null, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIS'], null, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIS'], null, 'real', false, $round, true)
          )
        ),
        'video' => array(
          'centro_oeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIV'], 3, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIV'], 3, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIV'], 3, 'real', false, $round, true)
          ),
          'nordeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIV'], 2, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIV'], 2, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIV'], 2, 'real', false, $round, true)
          ),
          'norte' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIV'], 1, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIV'], 1, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIV'], 1, 'real', false, $round, true)
          ),
          'sudeste' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIV'], 4, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIV'], 4, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIV'], 4, 'real', false, $round, true)
          ),
          'sul' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIV'], 5, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIV'], 5, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIV'], 5, 'real', false, $round, true)
          ),
          'mer_nacional' => array(
            'real' => $this->process_data_mean_region($result, ['MIDIAIV'], null, 'real', false, $round),
            'dollar' => $this->process_data_mean_region($result, ['MIDIAIV'], null, 'dollar', false, $round),
            'share' => $this->process_data_mean_region($result, ['MIDIAIV'], null, 'real', false, $round, true)
          )
        ),
      );
    }

    return $process_data;
  }

  private function get_data_old_state($post_id)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets_states";
    $sql = "SELECT * FROM `$table_spreadsheet` WHERE post_id = $post_id";
    $data = $wpdb->get_results($sql, ARRAY_A);

    return array(
      'acre' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'AC'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'AC'),
        'share' => $this->get_data_old($data, null, 'share', null, 'AC'),
      ),
      'alagoas' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'AL'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'AL'),
        'share' => $this->get_data_old($data, null, 'share', null, 'AL'),
      ),
      'amapa' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'AP'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'AP'),
        'share' => $this->get_data_old($data, null, 'share', null, 'AP'),
      ),
      'amazonas' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'AM'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'AM'),
        'share' => $this->get_data_old($data, null, 'share', null, 'AM'),
      ),
      'bahia' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'BA'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'BA'),
        'share' => $this->get_data_old($data, null, 'share', null, 'BA'),
      ),
      'ceara' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'CE'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'CE'),
        'share' => $this->get_data_old($data, null, 'share', null, 'CE'),
      ),
      'distrito_federal' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'DF'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'DF'),
        'share' => $this->get_data_old($data, null, 'share', null, 'DF'),
      ),
      'espirito_santo' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'ES'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'ES'),
        'share' => $this->get_data_old($data, null, 'share', null, 'ES'),
      ),
      'goias' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'GO'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'GO'),
        'share' => $this->get_data_old($data, null, 'share', null, 'GO'),
      ),
      'maranhao' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'MA'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'MA'),
        'share' => $this->get_data_old($data, null, 'share', null, 'MA'),
      ),
      'mato_grosso' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'MT'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'MT'),
        'share' => $this->get_data_old($data, null, 'share', null, 'MT'),
      ),
      'mato_grosso_do_sul' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'MS'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'MS'),
        'share' => $this->get_data_old($data, null, 'share', null, 'MS'),
      ),
      'minas_gerais' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'MG'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'MG'),
        'share' => $this->get_data_old($data, null, 'share', null, 'MG'),
      ),
      'para' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'PA'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'PA'),
        'share' => $this->get_data_old($data, null, 'share', null, 'PA'),
      ),
      'paraiba' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'PB'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'PB'),
        'share' => $this->get_data_old($data, null, 'share', null, 'PB'),
      ),
      'parana' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'PR'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'PR'),
        'share' => $this->get_data_old($data, null, 'share', null, 'PR'),
      ),
      'pernambuco' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'PE'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'PE'),
        'share' => $this->get_data_old($data, null, 'share', null, 'PE'),
      ),
      'piaui' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'PI'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'PI'),
        'share' => $this->get_data_old($data, null, 'share', null, 'PI'),
      ),
      'rio_de_janeiro' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'RJ'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'RJ'),
        'share' => $this->get_data_old($data, null, 'share', null, 'RJ'),
      ),
      'rio_grande_do_norte' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'RN'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'RN'),
        'share' => $this->get_data_old($data, null, 'share', null, 'RN'),
      ),
      'rio_grande_do_sul' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'RS'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'RS'),
        'share' => $this->get_data_old($data, null, 'share', null, 'RS'),
      ),
      'rondonia' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'RO'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'RO'),
        'share' => $this->get_data_old($data, null, 'share', null, 'RO'),
      ),
      'roraima' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'RR'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'RR'),
        'share' => $this->get_data_old($data, null, 'share', null, 'RR'),
      ),
      'santa_catarina' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'SC'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'SC'),
        'share' => $this->get_data_old($data, null, 'share', null, 'SC'),
      ),
      'sao_paulo' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'SP'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'SP'),
        'share' => $this->get_data_old($data, null, 'share', null, 'SP'),
      ),
      'sergipe' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'SE'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'SE'),
        'share' => $this->get_data_old($data, null, 'share', null, 'SE'),
      ),
      'tocantins' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'TO'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'TO'),
        'share' => $this->get_data_old($data, null, 'share', null, 'TO'),
      ),
      'brasil' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'MN'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'MN'),
        'share' => $this->get_data_old($data, null, 'share', null, 'MN'),
      ),
      'total' => array(
        'real' => $this->get_data_old($data, null, 'real', null, 'TOTAL'),
        'dollar' => $this->get_data_old($data, null, 'dollar', null, 'TOTAL'),
      )
    );
  }


  private function get_data_state($post_id, $round = false)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets";
    $table_states_region = $wpdb->prefix . "cm_states_region";
    $sql = "SELECT s.*, sr.region
    FROM `$table_spreadsheet` AS s
    LEFT JOIN `$table_states_region` AS sr ON (s.state = sr.state)
    WHERE s.post_id = $post_id";
    $data = $wpdb->get_results($sql, ARRAY_A);

    return array(
      'acre' => array(
        'real' => $this->process_data_state($data, 'acre', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'acre', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'acre', 'real', true, $round)
      ),
      'alagoas' => array(
        'real' => $this->process_data_state($data, 'alagoas', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'alagoas', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'alagoas', 'real', true, $round)
      ),
      'amapa' => array(
        'real' => $this->process_data_state($data, 'amapa', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'amapa', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'amapa', 'real', true, $round)
      ),
      'amazonas' => array(
        'real' => $this->process_data_state($data, 'amazonas', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'amazonas', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'amazonas', 'real', true, $round)
      ),
      'bahia' => array(
        'real' => $this->process_data_state($data, 'bahia', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'bahia', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'bahia', 'real', true, $round)
      ),
      'ceara' => array(
        'real' => $this->process_data_state($data, 'ceara', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'ceara', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'ceara', 'real', true, $round)
      ),
      'distrito_federal' => array(
        'real' => $this->process_data_state($data, 'distrito_federal', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'distrito_federal', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'distrito_federal', 'real', true, $round)
      ),
      'espirito_santo' => array(
        'real' => $this->process_data_state($data, 'espirito_santo', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'espirito_santo', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'espirito_santo', 'real', true, $round)
      ),
      'goias' => array(
        'real' => $this->process_data_state($data, 'goias', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'goias', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'goias', 'real', true, $round)
      ),
      'maranhao' => array(
        'real' => $this->process_data_state($data, 'maranhao', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'maranhao', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'maranhao', 'real', true, $round)
      ),
      'mato_grosso' => array(
        'real' => $this->process_data_state($data, 'mato_grosso', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'mato_grosso', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'mato_grosso', 'real', true, $round)
      ),
      'mato_grosso_do_sul' => array(
        'real' => $this->process_data_state($data, 'mato_grosso_do_sul', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'mato_grosso_do_sul', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'mato_grosso_do_sul', 'real', true, $round)
      ),
      'minas_gerais' => array(
        'real' => $this->process_data_state($data, 'minas_gerais', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'minas_gerais', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'minas_gerais', 'real', true, $round)
      ),
      'para' => array(
        'real' => $this->process_data_state($data, 'para', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'para', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'para', 'real', true, $round)
      ),
      'paraiba' => array(
        'real' => $this->process_data_state($data, 'paraiba', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'paraiba', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'paraiba', 'real', true, $round)
      ),
      'parana' => array(
        'real' => $this->process_data_state($data, 'parana', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'parana', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'parana', 'real', true, $round)
      ),
      'pernambuco' => array(
        'real' => $this->process_data_state($data, 'pernambuco', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'pernambuco', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'pernambuco', 'real', true, $round)
      ),
      'piaui' => array(
        'real' => $this->process_data_state($data, 'piaui', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'piaui', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'piaui', 'real', true, $round)
      ),
      'rio_de_janeiro' => array(
        'real' => $this->process_data_state($data, 'rio_de_janeiro', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'rio_de_janeiro', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'rio_de_janeiro', 'real', true, $round)
      ),
      'rio_grande_do_norte' => array(
        'real' => $this->process_data_state($data, 'rio_grande_do_norte', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'rio_grande_do_norte', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'rio_grande_do_norte', 'real', true, $round)
      ),
      'rio_grande_do_sul' => array(
        'real' => $this->process_data_state($data, 'rio_grande_do_sul', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'rio_grande_do_sul', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'rio_grande_do_sul', 'real', true, $round)
      ),
      'rondonia' => array(
        'real' => $this->process_data_state($data, 'rondonia', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'rondonia', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'rondonia', 'real', true, $round)
      ),
      'roraima' => array(
        'real' => $this->process_data_state($data, 'roraima', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'roraima', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'roraima', 'real', true, $round)
      ),
      'santa_catarina' => array(
        'real' => $this->process_data_state($data, 'santa_catarina', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'santa_catarina', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'santa_catarina', 'real', true, $round)
      ),
      'sao_paulo' => array(
        'real' => $this->process_data_state($data, 'sao_paulo', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'sao_paulo', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'sao_paulo', 'real', true, $round)
      ),
      'sergipe' => array(
        'real' => $this->process_data_state($data, 'sergipe', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'sergipe', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'sergipe', 'real', true, $round)
      ),
      'tocantins' => array(
        'real' => $this->process_data_state($data, 'tocantins', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'tocantins', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'tocantins', 'real', true, $round)
      ),
      'brasil' => array(
        'real' => $this->process_data_state($data, 'brasil', 'real', false, $round),
        'dollar' => $this->process_data_state($data, 'brasil', 'dollar', false, $round),
        'share' => $this->process_data_state($data, 'brasil', 'real', true, $round)
      ),
      'total' => array(
        'real' => $this->process_data_state($data, null, 'real', false, $round, true),
        'dollar' => $this->process_data_state($data, null, 'dollar', false, $round, true),
      )
    );
  }

  public function sum_mean($data, $means = [], $type, $total_general = false)
  {
    $total = 0;
    foreach ($data as $mean) {
      if (!$total_general) {
        if (in_array($mean['mean'], $means)) {
          $total += $mean[$type];
        }
      } else {
        $total += $mean[$type];
      }
    }
    return $total;
  }

  public function sum_region($data, $type, $region = null, $national = false)
  {
    $total = 0;
    foreach ($data as $mean) {
      if ($national) {
        if ($mean['state'] == 'BRASIL') {
          $total += $mean[$type];
        }
      } else {
        if ($mean['region'] == $region) {
          $total += $mean[$type];
        }
      }
    }
    return $total;
  }

  public function sum_mean_region($data, $means = [], $region, $type, $total_general = false)
  {
    $total = 0;
    foreach ($data as $value) {
      if (!$total_general) {
        if (in_array($value['mean'], $means) && $value['region'] == $region) {
          $total += $value[$type];
        }
      } else {
        if (is_null($region)) {
          if ($value['state'] == 'BRASIL') {
            $total += $value[$type];
          }
        } else {
          if ($value['region'] == $region) {
            $total += $value[$type];
          }
        }
      }
    }
    return $total;
  }

  public function sum_state($data, $region, $type, $total_general = false)
  {
    $total = 0;
    foreach ($data as $value) {
      $slug = $this->slugify($value['state']);
      //print_r($this->slugify($value['state']) . '--' . $region . '<br>');
      if ($total_general) {
        $total += $value[$type];
      } else {
        if ($slug == $region) {
          $total += $value[$type];
        }
      }
    }
    return $total;
  }

  private function process_data_mean_comunication($data, $means = [], $type, $share = false, $total_general = false, $round)
  {

    if ($total_general) {
      if ($round) {
        $value = $this->roundUpToNearestThousand($this->sum_mean($data, [], $type, true));
        return substr(number_format($value, 2, ',', '.'), 0, -7);
      } else {
        return $this->sum_mean($data, [], $type, true);
      }
    }

    if ($share) {
      return number_format((($this->sum_mean($data, $means, $type, false) / $this->sum_mean($data, [], $type, true)) * 100), 1, ',', '.');
    } else {
      if ($round) {
        $value = $this->roundUpToNearestThousand($this->sum_mean($data, $means, $type, false));
        return substr(number_format($value, 2, ',', '.'), 0, -7);
      } else {
        return $this->sum_mean($data, $means, $type, false);
      }
    }
  }

  private function process_share_mean_comunication($result, $means, $type)
  {
    $value = (($this->sum_mean($result, $means, $type, false) / $this->sum_mean($result, ['MIDIAIA', 'MIDIAIB', 'MIDIAID', 'MIDIAIN', 'MIDIAIS', 'MIDIAIV', 'MIDIAINT'], 'real', false)) * 100);
    return number_format($value, 1, ',', '.');
  }

  private function process_data_mean_region($data, $means = [], $region, $type, $total_general = false, $round = false, $share = false)
  {

    if ($total_general) {
      $value = $this->sum_mean_region($data, $means, $region, $type, true);
      if ($round) {
        return substr(number_format($this->roundUpToNearestThousand($value), 2, ',', '.'), 0, -7);
      } else {
        return $value;
      }
    }

    if ($share) {
      $value = ($this->sum_mean_region($data, $means, $region, $type, $total_general) / $this->sum_mean_region($data, $means, $region, $type, true)) * 100;
      return number_format($value, 1, ',', '.');
    } else {
      if ($round) {
        return substr(number_format($this->roundUpToNearestThousand($this->sum_mean_region($data, $means, $region, $type, $total_general)), 2, ',', '.'), 0, -7);
      } else {
        return $this->sum_mean_region($data, $means, $region, $type, $total_general);
      }
    }
  }

  private function process_data_state($data, $region, $type, $share = false, $round = false, $total_general = false)
  {

    if ($total_general) {
      if ($round) {
        return substr(number_format($this->roundUpToNearestThousand($this->sum_state($data, $region, $type, true)), 2, ',', '.'), 0, -7);
      } else {
        return $this->sum_state($data, $region, $type, true);
      }
    }

    if ($share) {
      $value = ($this->sum_state($data, $region, $type) / $this->sum_state($data, $region, $type, true)) * 100;
      return number_format($value, 2, ',', '.');
    } else {
      if ($round) {
        return substr(number_format($this->roundUpToNearestThousand($this->sum_state($data, $region, $type)), 2, ',', '.'), 0, -7);
      } else {
        return $this->sum_state($data, $region, $type);
      }
    }
  }

  private function process_data_region($data, $type, $region, $share = false, $total_general = false, $round = false)
  {

    if ($total_general) {
      $value = $this->sum_mean($data, [], $type, true);
      if ($round) {
        return substr(number_format($value, 2, ',', '.'), 0, -7);
      }
      return $value;
    }

    if ($share) {
      $value = (($this->sum_region($data, $type, $region, false) / $this->sum_mean($data, [], $type, true)) * 100);
      return number_format($value, 1, ',', '.');
    }
    if ($round) {
      return substr(number_format($this->sum_region($data, $type, $region, false), 2, ',', '.'), 0, -7);
    }
    return $this->sum_region($data, $type, $region, false);
  }

  private function get_data_old($data, $mean, $type, $region = null, $state = null)
  {
    foreach ($data as $row) {
      if (!empty($mean) && !empty($region) && empty($state)) {
        if ($row['mean'] == $mean && $row['region'] == $region) {
          return (!empty($row[$type])) ? $row[$type] : 0;
        }
      } else if (empty($mean) && !empty($region) && empty($state)) {
        if ($row['region'] == $region) {
          return (!empty($row[$type])) ? $row[$type] : 0;
        }
      } else if (empty($mean) && empty($region) && !empty($state)) {
        if ($row['state'] == $state) {
          return (!empty($row[$type])) ? $row[$type] : 0;
        }
      } else {
        if ($row['mean'] == $mean) {
          return (!empty($row[$type])) ? $row[$type] : 0;
        }
      }
    }
  }


  private function roundUpToNearestThousand($n, $increment = 1000)
  {
    return (int) ($increment * ceil($n / $increment));
  }
}
