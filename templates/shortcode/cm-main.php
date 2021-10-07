<?php
defined('ABSPATH') || exit;
?>
<div class="cenp-wrapper">
  <div class="cenp-container">
    <div class="cenp-row">
      <div class="cenp-sidebar-container">
        <nav class="cenp-sidebar">
          <div class="cenp-title">
            CENP Meios
          </div>
          <?php
          if (!empty($categories)) {
            foreach ($categories as $category) {
          ?>
              <div class="cenp-category">
                <h5><?php echo $category->name; ?></h5>
                <ul class="cenp-list">
                  <?php
                  $posts = $this->getPostsByTaxonomyId($category->term_id);
                  if (!empty($posts)) {
                    foreach ($posts as $post) {
                  ?>
                      <li>
                        <a href="javascript:void(0)" class="cenp-post-item" data-post="<?php echo $post->ID; ?>"><?php echo esc_attr($post->post_title); ?></a>
                      </li>
                  <?php
                    }
                  }
                  ?>
                </ul>
              </div>
          <?php
            }
          }
          ?>
        </nav>
      </div>
      <div class="cenp-main-container">
        <div class="cenp-main-wrapper loading"></div>
      </div>
    </div>
  </div>
</div>