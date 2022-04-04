<?php

defined('ABSPATH') || exit;

class Cenp_Meios_Admin extends Cenp_Meios_Utils
{

  public  function __construct()
  {
    add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    add_action('init', array($this, 'cm_register_post_type'));
    add_action('init', array($this, 'cm_register_taxonomy'));
    add_action('save_post', array($this, 'cm_save'));
    add_filter('mce_buttons_2', array($this, 'cm_mce_buttons_2'));

    add_action('cenp-ranking_add_form_fields', array($this, 'ranking_add_taxonomy_custom_fields'), 10, 2);
    add_action('cenp-ranking_edit_form_fields', array($this, 'ranking_edit_taxonomy_custom_fields'), 10, 2);

    add_action('created_cenp-ranking', array($this, 'save_term_fields'));
    add_action('edited_cenp-ranking', array($this, 'save_term_fields'));
  }

  public function admin_scripts()
  {
    global $typenow;
    if ($typenow != 'cenp-mean') {
      return;
    }

    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');

    wp_enqueue_style(
      'cm_bootstrap',
      '//cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css',
      array(),
      '4.6.0',
      'all'
    );
    wp_enqueue_script(
      'cm_bootstrap',
      '//cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js',
      array('jquery'),
      '4.6.0',
      true
    );
    wp_enqueue_script(
      'cm_maskedinput',
      '//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js',
      array('jquery', 'cm_bootstrap'),
      '1.4.1',
      true
    );
    wp_enqueue_script(
      'cm_xlsx',
      '//cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.3/xlsx.full.min.js',
      array('jquery'),
      CM_VERSION,
      true
    );
    wp_enqueue_script(
      'cm_validate',
      '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js',
      array('jquery'),
      CM_VERSION,
      true
    );
    wp_enqueue_script(
      'cm_validate-additional',
      '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/additional-methods.min.js',
      array('jquery', 'cm_validate'),
      CM_VERSION,
      true
    );
    wp_enqueue_script(
      'cm_scripts',
      apply_filters('cm_scripts_url', plugins_url('assets/js/cenp_meios_admin.js', CM_PATH_ROOT)),
      array('jquery', 'cm_maskedinput', 'cm_xlsx', 'cm_validate-additional', 'media-upload', 'thickbox'),
      CM_VERSION,
      true
    );

    wp_enqueue_style('thickbox');

    wp_enqueue_style(
      'cm_style',
      apply_filters('cm_style_url', plugins_url('assets/css/cenp_meios_admin.css', CM_PATH_ROOT)),
      array('thickbox'),
      CM_VERSION,
      'all'
    );
  }

  public function cm_register_post_type()
  {
    $labels = array(
      'name' => __('Cenp Meios', CM_TEXT_DOMAIN),
      'singular_name' => __('Cenp Meio', CM_TEXT_DOMAIN),
      'add_new' => __('Adicionar Novo', CM_TEXT_DOMAIN),
      'add_new_item' => __('Novo Item', CM_TEXT_DOMAIN),
      'edit_item' => __('Editar Item', CM_TEXT_DOMAIN),
      'new_item' => __('Novo Item', CM_TEXT_DOMAIN),
      'view_item' => __('Ver Item', CM_TEXT_DOMAIN),
      'search_items' => __('Procurar Itens', CM_TEXT_DOMAIN),
      'not_found' =>  __('Nenhum registro encontrado', CM_TEXT_DOMAIN),
      'not_found_in_trash' => __('Nenhum registro encontrado na lixeira', CM_TEXT_DOMAIN),
      'parent_item_colon' => '',
      'menu_name' => __('Cenp Meios', CM_TEXT_DOMAIN),
    );

    $args = array(
      'labels'                => $labels,
      'public'                => true,
      'public_queryable'      => true,
      'show_ui'               => true,
      'query_var'             => true,
      'rewrite'               => true,
      'capability_type'       => 'post',
      'has_archive'           => true,
      'hierarchical'          => false,
      'menu_position'         => 21,
      'menu_icon'             => 'dashicons-cloud-upload',
      'register_meta_box_cb'  => array($this, 'cm_register_meta_box'),
      'supports'              => array('title'),
    );

    register_post_type('cenp-mean', $args);
    flush_rewrite_rules();
  }

  public function cm_register_taxonomy()
  {

    // Paineis
    $labels = array(
      'name'                => __('Painéis', CM_TEXT_DOMAIN),
      'singular_name'       => __('Painel', CM_TEXT_DOMAIN),
      'search_items'        => __('Buscar Painéis', CM_TEXT_DOMAIN),
      'all_items'           => __('Todas os Painéis', CM_TEXT_DOMAIN),
      'parent_item'         => __('Painel Parental', CM_TEXT_DOMAIN),
      'parent_item_colon'   => __('Painel Parental:', CM_TEXT_DOMAIN),
      'edit_item'           => __('Editar Painel', CM_TEXT_DOMAIN),
      'update_item'         => __('Atualizar Painel', CM_TEXT_DOMAIN),
      'add_new_item'        => __('Adicionar Painel', CM_TEXT_DOMAIN),
      'new_item_name'       => __('Adicionar Painel', CM_TEXT_DOMAIN),
      'menu_name'           => __('Painéis', CM_TEXT_DOMAIN),
    );
    register_taxonomy(
      'cenp-category',
      'cenp-mean',
      array(
        'hierarchical'        => true,
        'labels'              => $labels,
        'show_ui'             => true,
        'show_admin_column'   => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'cenp-category'),
      )
    );

    // Ranking
    $labels_ranking = array(
      'name'                => __('Rankings', CM_TEXT_DOMAIN),
      'singular_name'       => __('Ranking', CM_TEXT_DOMAIN),
      'search_items'        => __('Buscar Ranking', CM_TEXT_DOMAIN),
      'all_items'           => __('Todas os Rankings', CM_TEXT_DOMAIN),
      'parent_item'         => __('Ranking Parental', CM_TEXT_DOMAIN),
      'parent_item_colon'   => __('Ranking Parental:', CM_TEXT_DOMAIN),
      'edit_item'           => __('Editar Ranking', CM_TEXT_DOMAIN),
      'update_item'         => __('Atualizar Ranking', CM_TEXT_DOMAIN),
      'add_new_item'        => __('Adicionar Ranking', CM_TEXT_DOMAIN),
      'new_item_name'       => __('Adicionar PaiRankingnel', CM_TEXT_DOMAIN),
      'menu_name'           => __('Rankings', CM_TEXT_DOMAIN),
    );
    register_taxonomy(
      'cenp-ranking',
      'cenp-mean',
      array(
        'hierarchical'        => true,
        'labels'              => $labels_ranking,
        'show_ui'             => true,
        'show_admin_column'   => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'cenp-ranking'),
      )
    );
  }

  public function ranking_add_taxonomy_custom_fields($tag)
  {
?>

    <tr class="form-field">
      <th scope="row" valign="top">
        <label for="show_modal"><?php _e('Exibir na modal de seleção:'); ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="show_modal" id="show_modal" value="yes"></label>
      </th>
    </tr>
    <tr class="form-field">
      <th scope="row" valign="top">
        <label for="show_history"><?php _e('Exibir histórico:'); ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="show_history" id="show_history" value="yes"></label>
      </th>
    </tr>

  <?php
  }

  public function ranking_edit_taxonomy_custom_fields($term, $taxonomy)
  {
    $modal = get_term_meta($term->term_id, 'show_modal', true);
    $history = get_term_meta($term->term_id, 'show_history', true);
  ?>

    <tr class="form-field">
      <th scope="row" valign="top">
        <label for="show_modal"><?php _e('Exibir na modal de seleção:'); ?></label>
      </th>
      <td>
        <input type="checkbox" name="show_modal" id="show_modal" value="yes" <?php echo ($modal == 'yes') ? 'checked' : ''; ?>>
      </td>
    </tr>

    <tr class="form-field">
      <th scope="row" valign="top">
        <label for="show_history"><?php _e('Exibir histórico:'); ?></label>
      </th>
      <td>
        <input type="checkbox" name="show_history" id="show_history" value="yes" <?php echo ($history == 'yes') ? 'checked' : ''; ?>>
      </td>
    </tr>

<?php
  }

  public function save_term_fields($term_id)
  {
    update_term_meta($term_id, 'show_modal', $_POST['show_modal']);
    update_term_meta($term_id, 'show_history', $_POST['show_history']);
  }

  public function cm_register_meta_box()
  {
    add_meta_box('meta_box_cm', __('Importar Planilha', CM_TEXT_DOMAIN), array($this, 'meta_box_cm_form'), 'cenp-mean', 'normal', 'high');
  }

  public function meta_box_cm_form($post)
  {
    $form_data = get_post_meta($post->ID, '_meios', true);
    Helpers::load_view('admin', compact('form_data'));
  }

  public function cm_save($post_id)
  {
    if (!isset($_POST['cm_nonce']) || !wp_verify_nonce($_POST['cm_nonce'], 'cm_nonce')) {
      return;
    }

    $form_data = array(
      'cm_period'               => $_POST['cm_period'],
      'cm_type'                 => $_POST['cm_type'],
      'cm_update'               => $_POST['cm_update'],
      'cm_description'          => $_POST['cm_description'],
      'cm_source_ranking'       => $_POST['cm_source_ranking'],
      'cm_source'               => $_POST['cm_source'],
      'cm_note'                 => $_POST['cm_note'],
      'cm_agency_title'         => $_POST['cm_agency_title'],
      'cm_agency_text'          => $_POST['cm_agency_text'],
      'cm_spreadsheet_type'     => $_POST['cm_spreadsheet_type'],
      'cm_source_real'          => $_POST['cm_source_real'],
      'cm_source_dollar'        => $_POST['cm_source_dollar'],
      'cm_source_midia'         => $_POST['cm_source_midia'],
      'cm_source_mercado'       => $_POST['cm_source_mercado'],
      'cm_source_meios_regioes' => $_POST['cm_source_meios_regioes'],
      'cm_source_estado'        => $_POST['cm_source_estado'],
      'cm_source_internet'      => $_POST['cm_source_internet'],
      'cm_description_footer'   => $_POST['cm_description_footer'],
      'cm_description_agency'   => $_POST['cm_description_agency'],
      'cm_agency_notes'         => $_POST['cm_agency_notes'],
      'cm_legends'              => $_POST['cm_legends'],
      'cm_description_estate'   => $_POST['cm_description_estate'],
    );

    if (!empty($_POST['cm_json'])) {
      switch ($_POST['cm_spreadsheet_type']) {
        case '1':
          $this->delete_spreadsheet_by_post($post_id);
          $this->insert_spreadsheet_by_post($post_id, $this->base64_to_array($_POST['cm_json']));
          break;
        case '2':
          $this->delete_old_spreadsheet_by_post($post_id);
          $this->insert_old_spreadsheet_by_post($post_id, $this->base64_to_array($_POST['cm_json']));
          break;
        case '3':
        case '4':
          $this->delete_spreadsheet_ranking_by_post($post_id);
          $this->insert_spreadsheet_ranking_by_post($post_id, $this->base64_to_array($_POST['cm_json']));
          break;
      }
    }

    if (!empty($_POST['cm_json_agency'])) {
      $this->delete_agencies_by_post($post_id);
      $this->insert_agencies_by_post($post_id, $this->base64_to_array($_POST['cm_json_agency']));
    }


    update_post_meta($post_id, '_meios', $form_data);
  }

  private function delete_agencies_by_post(int $post_id)
  {
    global $wpdb;
    $table_agencies = $wpdb->prefix . "cm_spreadsheets_agencies";
    $wpdb->delete($table_agencies, array('post_id' => intval($post_id)));
  }

  private function insert_agencies_by_post(int $post_id, array $values)
  {
    global $wpdb;
    $data = array_map(function ($item) use ($post_id) {
      return "($post_id,'" . addslashes(esc_sql(utf8_decode($item['nome']))) . "')";
    }, $values['agencias']);

    $table_agencies = $wpdb->prefix . "cm_spreadsheets_agencies";
    $sql = "INSERT INTO $table_agencies (`post_id`,`name`) VALUES " . implode(',', $data);
    $wpdb->query($sql);
  }


  private function delete_spreadsheet_by_post(int $post_id)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets";
    $wpdb->delete($table_spreadsheet, array('post_id' => intval($post_id)));
  }

  private function delete_old_spreadsheet_by_post(int $post_id)
  {
    global $wpdb;
    $table_spreadsheet_means = $wpdb->prefix . "cm_spreadsheets_means";
    $table_spreadsheet_means_regions = $wpdb->prefix . "cm_spreadsheets_means_regions";
    $table_spreadsheet_regions = $wpdb->prefix . "cm_spreadsheets_regions";
    $table_spreadsheet_states = $wpdb->prefix . "cm_spreadsheets_states";

    $wpdb->delete($table_spreadsheet_means, array('post_id' => intval($post_id)));
    $wpdb->delete($table_spreadsheet_means_regions, array('post_id' => intval($post_id)));
    $wpdb->delete($table_spreadsheet_regions, array('post_id' => intval($post_id)));
    $wpdb->delete($table_spreadsheet_states, array('post_id' => intval($post_id)));
  }

  private function delete_spreadsheet_ranking_by_post(int $post_id)
  {
    global $wpdb;
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets_ranking";
    $wpdb->delete($table_spreadsheet, array('post_id' => intval($post_id)));

    $table_spreadsheet_state = $wpdb->prefix . "cm_spreadsheets_ranking_state";
    $wpdb->delete($table_spreadsheet_state, array('post_id' => intval($post_id)));
  }

  private function base64_to_array(string $value)
  {
    return json_decode(utf8_encode(base64_decode($value)), true);
  }

  private function insert_spreadsheet_by_post(int $post_id, array $values)
  {
    global $wpdb;
    $data = array_map(function ($item) use ($post_id) {
      return "($post_id,'" . $item['uf'] . "','" . $item['meio'] . "','" . $item['real'] . "','" . $item['dolar'] . "')";
    }, $values['matriz']);
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets";
    $sql = "INSERT INTO $table_spreadsheet (`post_id`,`state`,`mean`,`real`,`dollar`) VALUES " . implode(',', $data);
    $wpdb->query($sql);
  }

  private function insert_spreadsheet_ranking_by_post(int $post_id, array $values)
  {
    global $wpdb;
    $data = array_map(function ($item) use ($post_id) {
      $name = (isset($item['nome'])) ? "'" . addslashes(esc_sql($item['nome'])) . "'" : 'null';
      return "($post_id,'" . esc_sql($item['posicao']) . "'," . $name . ",'" . esc_sql($item['uf']) . "','" . esc_sql($item['posicao_anterior']) . "')";
    }, $values['ranking']);
    $table_spreadsheet = $wpdb->prefix . "cm_spreadsheets_ranking";
    $sql = "INSERT INTO $table_spreadsheet (`post_id`,`position`,`name`,`state`, `last_position`) VALUES " . implode(',', $data);
    $wpdb->query($sql);

    if (!empty($values['estado'])) {
      $data = array_map(function ($item) use ($post_id) {
        $name = (isset($item['nome'])) ? "'" . addslashes(esc_sql($item['nome'])) . "'" : 'null';
        return "($post_id,'" . esc_sql($item['posicao']) . "'," . $name . ",'" . esc_sql($item['uf']) . "')";
      }, $values['estado']);
      $table_spreadsheet_state = $wpdb->prefix . "cm_spreadsheets_ranking_state";
      $sql = "INSERT INTO $table_spreadsheet_state (`post_id`,`position`,`name`,`state`) VALUES " . implode(',', $data);
      $wpdb->query($sql);
    }
  }


  private function insert_old_spreadsheet_by_post(int $post_id, array $values)
  {
    global $wpdb;

    $table_spreadsheet_means = $wpdb->prefix . "cm_spreadsheets_means";
    $table_spreadsheet_means_regions = $wpdb->prefix . "cm_spreadsheets_means_regions";
    $table_spreadsheet_regions = $wpdb->prefix . "cm_spreadsheets_regions";
    $table_spreadsheet_states = $wpdb->prefix . "cm_spreadsheets_states";

    if (!empty($values['meios'])) {
      $means = array_map(function ($item) use ($post_id) {
        return "($post_id,'" . $item['meio'] . "','" . $item['real'] . "','" . $item['dolar'] . "','" . $item['share'] . "')";
      }, $values['meios']);

      $sql = "INSERT INTO $table_spreadsheet_means (`post_id`,`mean`,`real`,`dollar`,`share`) VALUES " . implode(',', $means);
      $wpdb->query($sql);
    }

    if (!empty($values['regioes'])) {
      $regions = array_map(function ($item) use ($post_id) {
        return "($post_id,'" . $item['regiao'] . "','" . $item['real'] . "','" . $item['dolar'] . "','" . $item['share'] . "')";
      }, $values['regioes']);

      $sql = "INSERT INTO $table_spreadsheet_regions (`post_id`,`region`,`real`,`dollar`,`share`) VALUES " . implode(',', $regions);
      $wpdb->query($sql);
    }

    if ($values['meios_regioes']) {
      $means_regions = array_map(function ($item) use ($post_id) {
        return "($post_id,'" . $item['meio'] . "','" . $item['real'] . "','" . $item['dolar'] . "','" . $item['share'] . "','" . $item['regiao'] . "')";
      }, $values['meios_regioes']);

      $sql = "INSERT INTO $table_spreadsheet_means_regions (`post_id`,`mean`,`real`,`dollar`,`share`,`region`) VALUES " . implode(',', $means_regions);
      $wpdb->query($sql);
    }

    if ($values['estados']) {
      $states = array_map(function ($item) use ($post_id) {
        return "($post_id,'" . $item['uf'] . "','" . $item['real'] . "','" . $item['dolar'] . "','" . $item['share'] . "')";
      }, $values['estados']);

      $sql = "INSERT INTO $table_spreadsheet_states (`post_id`,`state`,`real`,`dollar`,`share`) VALUES " . implode(',', $states);
      $wpdb->query($sql);
    }
  }

  public function cm_mce_buttons_2($buttons)
  {
    $buttons[] = 'superscript';
    $buttons[] = 'subscript';
    $buttons[] = 'code';
    return $buttons;
  }
}
