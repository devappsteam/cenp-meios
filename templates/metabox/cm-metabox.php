<?php
defined('ABSPATH') || exit;
?>
<div class="cenp-container">
  <div class="cenp-row">
    <div class="cenp-col-50">
      <a href="<?php echo plugins_url() . '/cenp-meios/assets/matriz/matriz.xlsx'; ?>" class="cenp-button" download>Baixar Modelo de Planilha</a>
    </div>
  </div>
  <div class="cenp-row">
    <div class="cenp-col-50">
      <div class="cenp-row">
        <div class="cenp-col-25">
          <label for="ci-period">Periodo de Apuração</label>
          <p>Selecione a qual período a importação corresponde.</p>
          <select id="ci-period" name="ci-period" required>
            <option value="" selected>Selecione</option>
            <option value="1" <?php echo ($period == 1) ? "selected" : null; ?>>JAN A MAR</option>
            <option value="2" <?php echo ($period == 2) ? "selected" : null; ?>>JAN A JUN</option>
            <option value="3" <?php echo ($period == 3) ? "selected" : null; ?>>JAN A SET</option>
            <option value="4" <?php echo ($period == 4) ? "selected" : null; ?>>JAN A DEZ</option>
          </select>
        </div>
        <div class="cenp-col-25">
          <label for="ci-type">Tipo de Importação</label>
          <p>Será efetuado a importação de Meios ou Ranking?</p>
          <div class="ci-type-wrapper">
            <label for="ci-type-meio">
              <input type="radio" name="ci-type" id="ci-type-meio" value="1" <?php echo $meio; ?>> Meio
            </label>
            <label for="ci-type-ranking">
              <input type="radio" name="ci-type" id="ci-type-ranking" value="2" <?php echo $ranking; ?>> Ranking
            </label>
            <label for="ci-type-ranking-uf">
              <input type="radio" name="ci-type" id="ci-type-ranking-uf" value="3" <?php echo $ranking_uf; ?>> Ranking por UF
            </label>
          </div>
        </div>
        <div class="cenp-col-25">
          <label for="ci-file">Arquivo</label>
          <p>Selecione o arquivo que será utilizado na extração dos dados.</p>
          <input type="file" name="ci-file" id="ci-file" accept=".xls,.xlsx">
          <input type="hidden" name="ci-file-json" id="ci-file-json" value="<?php echo $json; ?>">
        </div>
      </div>
      <div class="cenp-row">
        <div class="cenp-col-100">
          <label for="ci-description">Descrição</label>
          <?php
          wp_editor($description, 'ci-description', array(
            'wpautop'       =>  true,
            'media_buttons' =>  false,
            'textarea_name' =>  'ci-description',
            'textarea_rows' =>  10,
            'teeny'         =>  true
          ));
          ?>
        </div>
      </div>
      <div class="cenp-row">
        <div class="cenp-col-100">
          <div class="cm-source">
            <label for="ci_source">Fonte</label>
            <?php
            wp_editor($source, 'ci-source', array(
              'wpautop'       =>  true,
              'media_buttons' =>  false,
              'textarea_name' =>  'ci-source',
              'textarea_rows' =>  10,
              'teeny'         =>  true
            ));
            ?>
          </div>
        </div>
      </div>
      <div class="cenp-row">
        <div class="cenp-col-100">
          <div class="cm-note">
            <label for="ci_source">Nota Técnica do CTCM</label>
            <?php
            wp_editor($note, 'ci-note', array(
              'wpautop'       =>  true,
              'media_buttons' =>  false,
              'textarea_name' =>  'ci-note',
              'textarea_rows' =>  10,
              'teeny'         =>  true
            ));
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>