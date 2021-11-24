<div class="cm-section cm-section--white">
  <div class="cm-container">
    <div class="cm-description">
      <h4 class="cm-center cm-uppercase"><?php echo $post_meta['cm_description']; ?></h4>
    </div>
    <div class="cm-table-responsive">
      <table class="cm-table">
        <thead>
          <tr>
            <th>POSIÇÃO</th>
            <th>RAZÃO SOCIAL</th>
            <th>UF</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $notes = $post_meta['cm_agency_notes'];

          for ($i = 0; $i < count($data_ranking); $i++) {
            $note_final = "";
            if (!empty($notes)) {
              foreach ($notes as $note) {
                if ($note['position'] == $data_ranking[$i]['position']) {
                  $note_final = trim($note['note']);
                }
              }
            }
          ?>
            <tr>
              <td><?php echo $data_ranking[$i]['position']; ?></td>
              <td><a href="<?php echo (!empty($note_final)) ? get_bloginfo('url') . '/' . $note_final : '#'; ?>" target="_blank" class="cm-note <?php echo (!empty($note_final)) ? 'has-note' : ''; ?>" style="cursor:<?php echo (!empty($note_final)) ? 'pointer !important' : 'auto !important'; ?>;"><?php echo stripslashes($data_ranking[$i]['name']); ?></a></td>
              <td><?php echo $data_ranking[$i]['state']; ?></td>
            </tr>
          <?php
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="cm-section cm-section--white">
  <div class="cm-container">
    <div class="cm-description">
      <h4 class="cm-center cm-uppercase"><?php echo $post_meta['cm_description_estate']; ?></h4>
    </div>
    <div class="cm-table-responsive">
      <table class="cm-table">
        <thead>
          <tr>
            <th>POSIÇÃO AG-UF</th>
            <th>RAZÃO SOCIAL</th>
            <th>UF</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $state = 'AL';
          $mark = false;
			
          for ($i = 0; $i < count($data_ranking_uf); $i++) {
          ?>
            <tr class="<?php echo ($mark) ? 'tr-blue' : 'tr-white'; ?>">
              <td><?php echo $data_ranking_uf[$i]['position']; ?></td>
              <td><?php echo stripslashes($data_ranking_uf[$i]['name']); ?></td>
              <td><?php echo $data_ranking_uf[$i]['state']; ?></td>
            </tr>
          <?php
            if ($i <= (count($data_ranking_uf) - 1)) {
              if ($data_ranking_uf[$i + 1]['state'] != $state) {
                $state = $data_ranking_uf[$i + 1]['state'];
                $mark = !$mark;
              }
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>