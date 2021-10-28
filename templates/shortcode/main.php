<div class="cm-wrapper">
  <!-- SIDEBAR -->
  <div class="cm-sidebar">
    <div class="cm-sidebar-wrapper">
      <div class="cm-header">
        <h2 class="cm-center">Meios</h2>
      </div>
      <div class="cm-items">
        <?php
        if (!empty($categories)) {
          foreach ($categories as $category) {
        ?>
            <div class="cm-category">
              <h5><?php echo $category->name; ?></h5>
              <ul class="cm-list">
                <?php
                $posts = $this->getPostsByTaxonomyId($category->term_id);
                if (!empty($posts)) {
                  foreach ($posts as $post) {
                    $meta = get_post_meta($post->ID, '_meios', true);
                    if ($is_ranking) {
                      if ($meta['cm_type'] == 2 || $meta['cm_type'] == 3) {
                ?>
                        <li>
                          <a href="javascript:void(0)" class="cm-item" data-post="<?php echo $post->ID; ?>"><?php echo esc_attr($post->post_title); ?></a>
                        </li>
                      <?php
                      }
                    } else {
                      if ($meta['cm_type'] == 1) {
                      ?>
                        <li>
                          <a href="javascript:void(0)" class="cm-item" data-post="<?php echo $post->ID; ?>"><?php echo esc_attr($post->post_title); ?></a>
                        </li>
                <?php
                      }
                    }
                  }
                }
                ?>
              </ul>
            </div>
        <?php
          }
        }
        ?>
      </div>
      <div class="cm-sidebar-footer">
        <ul class="cm-list">
          <li>
            <a href="/cenp-meios/#cm_cronog" class="cm-link">Cronograma</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <!-- END SIDEBAR -->
  <!-- MAIN -->
  <div class="cm-main">
    <div class="cm-content"></div>
  </div>
  <!-- END MAIN -->
</div>