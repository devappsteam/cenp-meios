<div class="cm-description">
  <h4><?php echo $post_meta['cm_description']; ?></h4>
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
      for ($i = 0; $i < count($data_ranking); $i++) {
      ?>
        <tr>
          <td><?php echo $data_ranking[$i]['position']; ?></td>
          <td><?php echo stripslashes($data_ranking[$i]['name']); ?></td>
          <td><?php echo $data_ranking[$i]['state']; ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
</div>