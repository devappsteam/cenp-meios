<?php

// Verifica o acesso direto
defined('ABSPATH') || exit;
wp_nonce_field('cm_nonce', 'cm_nonce');

$mean = 'checked';
$round_yes = 'checked';
$round_no = '';
$type_rank = '';
$type_rank_uf = '';
$file_required = (empty($form_data)) ? 'required' : '';

if (isset($form_data['cm_round'])) {
  switch ($form_data['cm_round']) {
    case 1:
    default:
      $round_yes = 'checked';
      $round_no = '';
      break;
    case 2:
      $round_yes = '';
      $round_no = 'checked';
      break;
  }
}

if (isset($form_data['cm_type'])) {
  switch ($form_data['cm_type']) {
    case 1:
    default:
      $mean = 'checked';
      $type_rank = '';
      $type_rank_uf = '';
      break;
    case 2:
      $mean = '';
      $type_rank = 'checked';
      $type_rank_uf = '';
      break;
    case 3:
      $mean = '';
      $type_rank = '';
      $type_rank_uf = 'checked';
      break;
  }
}

?>
<div class="container-fluid">
  <div class="row">
    <div class="col-12 mt-4 mb-4" id="cm_alerts">
      <div class="alert alert-green fade show alert-fixed" role="alert">
        <strong>Atenção!</strong> A importação depende da matriz padrão para efetuar o carregamentos dos dados, caso não tenha a matriz atualizada clique no link para efetuar o download.
        <br>
        <a href="<?php echo plugins_url('matriz_nova.xlsx', CM_PATH_ROOT); ?>" download><b>Matriz dinâmica - Meios</b></a> |
        <a href="<?php echo plugins_url('matriz_antiga.xlsx', CM_PATH_ROOT); ?>" download><b>Matriz estática - Meios</b></a> |
        <a href="<?php echo plugins_url('ranking.xlsx', CM_PATH_ROOT); ?>" download><b>Matriz - Ranking</b></a> |
        <a href="<?php echo plugins_url('ranking_uf.xlsx', CM_PATH_ROOT); ?>" download><b>Matriz - Ranking por Estado</b></a>
      </div>
    </div>
  </div>
  <div class="form-row">
    <div class="col-12 col-md-12 col-lg-6 form-group">
      <label class="form-label font-weight-bold"><?php echo __('Tipo de Planilha', CM_TEXT_DOMAIN) ?></label>
      <p class="text-muted"><?php echo __('Selecione o tipo de planilha correspondente.', CM_TEXT_DOMAIN); ?></p>
      <select class="form-control" name="cm_spreadsheet_type" required>
        <option value=""><?php echo __('Selecionar', CM_TEXT_DOMAIN); ?></option>
        <option value="1" <?php echo (isset($form_data['cm_spreadsheet_type']) && $form_data['cm_spreadsheet_type'] == 1) ? 'selected' : null; ?>><?php echo __('Matriz dinâmica - Meios', CM_TEXT_DOMAIN); ?></option>
        <option value="2" <?php echo (isset($form_data['cm_spreadsheet_type']) && $form_data['cm_spreadsheet_type'] == 2) ? 'selected' : null; ?>><?php echo __('Matriz estática - Meios', CM_TEXT_DOMAIN); ?></option>
        <option value="3" <?php echo (isset($form_data['cm_spreadsheet_type']) && $form_data['cm_spreadsheet_type'] == 3) ? 'selected' : null; ?>><?php echo __('Matriz - Ranking', CM_TEXT_DOMAIN); ?></option>
        <option value="4" <?php echo (isset($form_data['cm_spreadsheet_type']) && $form_data['cm_spreadsheet_type'] == 4) ? 'selected' : null; ?>><?php echo __('Matriz - Ranking por Estado', CM_TEXT_DOMAIN); ?></option>
      </select>
    </div>
    <div class="col-12 col-md-12 col-lg-6 form-group">
      <label class="form-label font-weight-bold"><?php echo __('Periodo de Apuração', CM_TEXT_DOMAIN) ?></label>
      <p class="text-muted"><?php echo __('Selecione o período de apuração correspondentes.', CM_TEXT_DOMAIN); ?></p>
      <select class="form-control" name="cm_period" required>
        <option value=""><?php echo __('Selecionar', CM_TEXT_DOMAIN); ?></option>
        <option value="1" <?php echo (isset($form_data['cm_period']) && $form_data['cm_period'] == 1) ? 'selected' : null; ?>><?php echo __('JAN A MAR', CM_TEXT_DOMAIN); ?></option>
        <option value="2" <?php echo (isset($form_data['cm_period']) && $form_data['cm_period'] == 2) ? 'selected' : null; ?>><?php echo __('JAN A JUN', CM_TEXT_DOMAIN); ?></option>
        <option value="3" <?php echo (isset($form_data['cm_period']) && $form_data['cm_period'] == 3) ? 'selected' : null; ?>><?php echo __('JAN A SET', CM_TEXT_DOMAIN); ?></option>
        <option value="4" <?php echo (isset($form_data['cm_period']) && $form_data['cm_period'] == 4) ? 'selected' : null; ?>><?php echo __('JAN A DEZ', CM_TEXT_DOMAIN); ?></option>
      </select>
    </div>
    <div class="col-12 col-md-12 col-lg-6 form-group">
      <label class="form-label font-weight-bold"><?php echo __('Tipo de Painel', CM_TEXT_DOMAIN) ?></label>
      <p class="text-muted"><?php echo __('Selecione o tipo de painel a ser criado.', CM_TEXT_DOMAIN); ?></p>
      <div class="form-check form-check-inline mt-2">
        <input class="form-check-input" type="radio" name="cm_type" id="cm_type_mean" value="1" <?php echo $mean; ?>>
        <label class="form-check-label font-weight-bold" for="cm_type_mean"><?php echo __('Meio', CM_TEXT_DOMAIN); ?></label>
      </div>
      <div class="form-check form-check-inline mt-2">
        <input class="form-check-input" type="radio" name="cm_type" id="cm_type_rank" value="2" <?php echo $type_rank; ?>>
        <label class="form-check-label font-weight-bold" for="cm_type_rank"><?php echo __('Ranking', CM_TEXT_DOMAIN); ?></label>
      </div>
      <div class="form-check form-check-inline mt-2">
        <input class="form-check-input" type="radio" name="cm_type" id="cm_type_rank_state" value="3" <?php echo $type_rank_uf; ?>>
        <label class="form-check-label font-weight-bold" for="cm_type_rank_state"><?php echo __('Ranking por UF', CM_TEXT_DOMAIN); ?></label>
      </div>
    </div>
    <div class="col-12 col-md-12 col-lg-6 form-group">
      <label class="form-label font-weight-bold"><?php echo __('Planilha', CM_TEXT_DOMAIN) ?></label>
      <p class="text-muted"><?php echo __('Selecione o arquivo XLSX para efetuar a importação.', CM_TEXT_DOMAIN); ?></p>
      <div class="custom-file">
        <input type="file" class="custom-file-input" id="cm_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" <?php echo $file_required; ?>>
        <input type="hidden" name="cm_json" id="cm_json" value="">
        <label class="custom-file-label" for="cm_file">Selecione</label>
      </div>
    </div>
  </div>
  <div class="form-row">
    <div class="col-12">
      <p class="h4 mt-4 mb-4"><?php echo __('Referências', CM_TEXT_DOMAIN); ?></p>
    </div>
    <div class="col-12 col-md-12 col-lg-3 form-group">
      <label class="form-label font-weight-bold"><?php echo __('Valor Faturado', CM_TEXT_DOMAIN) ?></label>
      <input type="number" class="form-control" name="cm_source_real" id="cm_source_real" value="<?php echo (isset($form_data['cm_source_real'])) ? $form_data['cm_source_real'] : '' ?>" min="1" max="100">
    </div>
    <div class="col-12 col-md-12 col-lg-3 form-group">
      <label class="form-label font-weight-bold"><?php echo __('Dólar', CM_TEXT_DOMAIN) ?></label>
      <input type="number" class="form-control" name="cm_source_dollar" id="cm_source_dollar" value="<?php echo (isset($form_data['cm_source_dollar'])) ? $form_data['cm_source_dollar'] : '' ?>" min="1" max="100">
    </div>
    <div class="col-12 col-md-12 col-lg-3 form-group">
      <label class="form-label font-weight-bold"><?php echo __('Mídias', CM_TEXT_DOMAIN) ?></label>
      <input type="number" class="form-control" name="cm_source_midia" id="cm_source_midia" value="<?php echo (isset($form_data['cm_source_midia'])) ? $form_data['cm_source_midia'] : '' ?>" min="1" max="100">
    </div>
    <div class="col-12 col-md-12 col-lg-3 form-group">
      <label class="form-label font-weight-bold"><?php echo __('Mercado Nacional', CM_TEXT_DOMAIN) ?></label>
      <input type="number" class="form-control" name="cm_source_mercado" id="cm_source_mercado" value="<?php echo (isset($form_data['cm_source_mercado'])) ? $form_data['cm_source_mercado'] : '' ?>" min="1" max="100">
    </div>
  </div>
  <div class="form-row">
    <div class="col-12 form-group mt-4">
      <label class="form-label font-weight-bold" for="cm_description"><?php echo __('Descrição', CM_TEXT_DOMAIN); ?></label>
      <?php
      wp_editor((isset($form_data['cm_description'])) ? $form_data['cm_description'] : '', 'cm_description', array(
        'wpautop'       =>  true,
        'media_buttons' =>  false,
        'textarea_name' =>  'cm_description',
        'textarea_rows' =>  10,
        'teeny'         =>  false,
        'quicktags' => array(
          // Items for the Text Tab
          'buttons' => 'strong,em,underline,ul,ol,li,link,code'
        )
      ));
      ?>
    </div>
  </div>
  <div class="form-row">
    <div class="col-12 form-group mt-4">
      <label class="form-label font-weight-bold" for="cm_source"><?php echo __('Fonte', CM_TEXT_DOMAIN); ?></label>
      <?php
      wp_editor((isset($form_data['cm_source'])) ? $form_data['cm_source'] : '', 'cm_source', array(
        'wpautop'       =>  true,
        'media_buttons' =>  false,
        'textarea_name' =>  'cm_source',
        'textarea_rows' =>  10,
        'teeny'         =>  false
      ));
      ?>
    </div>
  </div>
  <div class="form-row">
    <div class="col-12 form-group mt-4">
      <label class="form-label font-weight-bold" for="cm_note"><?php echo __('Nota Técnica do CTCM', CM_TEXT_DOMAIN); ?></label>
      <?php
      wp_editor((isset($form_data['cm_note'])) ? $form_data['cm_note'] : '', 'cm_note', array(
        'wpautop'       =>  true,
        'media_buttons' =>  false,
        'textarea_name' =>  'cm_note',
        'textarea_rows' =>  10,
        'teeny'         =>  false
      ));
      ?>
    </div>
  </div>
  <div class="form-row">
    <div class="col-12">
      <p class="h4"><?php echo __('Agências Participantes', CM_TEXT_DOMAIN); ?></p>
    </div>
    <div class="col-12 col-lg-6 mt-4 form-group">
      <label class="form-label font-weight-bold" for="cm_agency_title">Título</label>
      <input type="text" class="form-control" name="cm_agency_title" id="cm_agency_title" value="<?php echo (isset($form_data['cm_agency_title'])) ? $form_data['cm_agency_title'] : '' ?>">
    </div>
    <div class="col-12 col-lg-6 mt-4 form-group">
      <label class="form-label font-weight-bold" for="cm_agency_file">Planilha</label>
      <div class="custom-file">
        <input type="file" class="custom-file-input" name="cm_agency_file" id="cm_agency_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
        <label class="custom-file-label" for="cm_agency_file">Selecione</label>
      </div>
    </div>
  </div>
</div>