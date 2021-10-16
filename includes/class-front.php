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
    wp_enqueue_style('cm_main', plugins_url('assets/css/cenp_meios.css', CM_PATH_ROOT), array(), CM_VERSION . '.' . time(), 'all');
    wp_enqueue_script('cm_google_chart', '//www.gstatic.com/charts/loader.js', array(), CM_VERSION, true);
    wp_enqueue_script('cm_main', plugins_url('assets/js/cenp_meios.js', CM_PATH_ROOT), array('jquery', 'cm_google_chart'), CM_VERSION . '.' . time(), true);
    wp_localize_script('cm_main', 'cenp_obj', array(
      'ajax_url' => admin_url('admin-ajax.php')
    ));
  }

  public function create_shortcode()
  {
    $categories = $this->getTaxonomies('cenp-category');
    include_once(dirname(dirname(__FILE__)) . '/templates/shortcode/main.php');
  }

  public function getTaxonomies(string $taxonomy)
  {
    return get_terms(array(
      'taxonomy'         => $taxonomy,
      'hide_empty'     => true,
      'orderBy'        => 'name',
      'order'            => 'DESC',
    ));
  }

  public function getPostsByTaxonomyId(int $taxonomy)
  {
    return query_posts(
      array(
        'post_type' => 'cenp-mean',
        'tax_query' => array(
          array(
            'taxonomy' => 'cenp-category',
            'terms' => $taxonomy,
            'field' => 'term_id',
          )
        ),
        'orderby' => 'title',
        'order' => 'DESC'
      )
    );
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
    $header = file_get_contents(dirname(dirname(__FILE__)) . '/templates/shortcode/partials/header.php');
    $source_note = file_get_contents(dirname(dirname(__FILE__)) . '/templates/shortcode/partials/source.php');

    // HTML - HEADER
    $html = str_replace('[LOGO_URL]', plugins_url() . '/cenp-mean/assets/images/logo.png', $header);
    $html = str_replace('[TITLE]', $post->post_title, $html);

	  
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
    }
    // HTML - SOURCE + NOTE
    $html .= str_replace('[SOURCE]', $post_meta['cm_source'], $source_note);
    $html = str_replace('[NOTE]', $post_meta['cm_note'], $html);
    $html = str_replace('[DISPLAY]', (empty($post_meta['cm_note'])) ? 'display: none;' : '', $html);
    $html = str_replace('[AGENCY_TITLE]', $post_meta['cm_agency_title'], $html);
    $html = str_replace('[AGENCY_TEXT]', $post_meta['cm_agency_text'], $html);
	$html = str_replace('[TABLE_TITLE]', $table_title, $html);
	$html = str_replace('[SOURCE_FONT]', $post_meta['cm_source_real'], $html);
	$html = str_replace('[SOURCE_DOLLAR]', $post_meta['cm_source_dollar'], $html);
    echo $html;
    wp_die();
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
    $round = ($post_meta['cm_round'] == "1") ? true : false;
    $data = $this->get_data_mean_comunication($post->ID, $round);
    include_once(dirname(dirname(__FILE__)) . '/templates/shortcode/partials/table-mean-comunication.php');
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_mean_region($post, $post_meta)
  {
    ob_start();
    $round = ($post_meta['cm_round'] == "1") ? true : false;
    $data = $this->get_data_mean_region($post->ID, $round);
    include_once(dirname(dirname(__FILE__)) . '/templates/shortcode/partials/table-mean-region.php');
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_region($post, $post_meta)
  {
    ob_start();
    $round = ($post_meta['cm_round'] == "1") ? true : false;
    $data = $this->get_data_region($post->ID, $round);
    include_once(dirname(dirname(__FILE__)) . '/templates/shortcode/partials/table-region.php');
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  public function render_state($post, $post_meta)
  {
    ob_start();
    $round = ($post_meta['cm_round'] == "1") ? true : false;
    $data = $this->get_data_state($post->ID, $round);
    include_once(dirname(dirname(__FILE__)) . '/templates/shortcode/partials/table-state.php');
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
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
    exit;
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



  private function roundUpToNearestThousand($n, $increment = 1000)
  {
    return (int) ($increment * ceil($n / $increment));
  }
}
