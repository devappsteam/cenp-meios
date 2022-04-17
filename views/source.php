<?php
if (isset($post_meta['cm_source']) && !empty($post_meta['cm_source'])) {
?>
  <div class="cm-section cm-section--white">
    <div class="cm-title cm-title--left cm-title--dark">
      <h2 class="cm-title__label">Fonte</h2>
    </div>
    <div class="cm-container">
      <?php echo $post_meta['cm_source']; ?>
    </div>
  </div>
<?php
}
?>