<div class="cm-description">
  <h4><?php echo $post_meta['cm_description']; ?></h4>
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
      for ($i = 0; $i < count($data_ranking); $i++) {
      ?>
        <tr class="<?php echo ($mark) ? 'tr-blue' : 'tr-white'; ?>">
          <td><?php echo $data_ranking[$i]['position']; ?></td>
          <td><?php echo $data_ranking[$i]['name']; ?></td>
          <td><?php echo $data_ranking[$i]['state']; ?></td>
        </tr>
      <?php
        if ($i <= (count($data_ranking) - 1)) {
          if ($data_ranking[$i + 1]['state'] != $state) {
            $state = $data_ranking[$i + 1]['state'];
            $mark = !$mark;
          }
        }
      }
      ?>
    </tbody>
  </table>
</div>