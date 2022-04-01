<div class="cm-wrapper">
  <div class="cm-header">
    <h1 class="cm-header__title">
      Cenp-Meios<br><?php echo $title; ?>
    </h1>
  </div>
  <div class="cm-tools header">
    <div class="cm-tools__items tooltip">
      <a href="/cenp-meio" class="cm-btn" <?php echo (!$is_ranking) ? 'id="cm_meios"' : '' ?> title="Painéis">Painéis</a>
	  <span class="tooltiptext">
		  Clique para selecionar um Painel
	  </span>
    </div>
    <div class="cm-tools__items tooltip">
      <a href="/cenp-ranking" class="cm-btn cm-btn--link" <?php echo ($is_ranking) ? 'id="cm_meios"' : '' ?> title="Ranking">Ranking</a>
	  <span class="tooltiptext">
	  	Clique para selecione um Ranking
	  </span>
    </div>
    <div class="cm-tools__items">
      <a href="/cenpmeios/#cm_cronog" class="cm-btn cm-btn--link" title="Cronograma">Cronograma</a>
    </div>
  </div>
  <div class="cm-main">
    <div class="svg-loader">
      <svg class="svg-container" height="50" width="50" viewBox="0 0 100 100">
        <circle class="loader-svg bg" cx="50" cy="50" r="45"></circle>
        <circle class="loader-svg animate" cx="50" cy="50" r="45"></circle>
      </svg>
    </div>
    <div class="cm-panels" id="cm_panels_wrapper"></div>
  </div>

  <!-- MODAL -->
  <div class="cm-modal" id="cm-modal">
    <div class="cm-modal-content">
      <div class="cm-modal__header">
        <h3 class="cm-modal__title">Selecione um <?php echo (!$is_ranking) ? 'meio' : 'ranking'; ?></h3>
        <button class="cm-modal__close" data-button="close">
          <svg height="24" width="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path stroke="#333" stroke-linecap="round" stroke-linejoin="round" d="M16.95 7.049l-9.901 9.9m9.9 0l-9.9-9.9"></path>
          </svg>
        </button>
      </div>
      <div class="cm-modal__body">
        <p class="cm-modal-legend"><?php echo __('Clique para selecionar um painel/ranking.', CM_TEXT_DOMAIN); ?></p>
        <ul class="cm-list">
          <?php
          foreach ($categories as $category) {
            if ($category->posts) {
          ?>
              <li class="cm-list__item cm-bold cm-my-10">
                <?php echo $category->name; ?>
              </li>
              <?php
              foreach ($category->posts as $post) {
                $meta = get_post_meta($post->ID, '_meios', true);
                if ($is_ranking) {
                  if ($meta['cm_type'] == 2 || $meta['cm_type'] == 3) {
              ?>
                    <li class="cm-list__item">
                      <span class="cm-checked"></span>
                      <a href="javascript:void(0);" data-post="<?php echo $post->ID; ?>" class="cm-list__link"><?php echo $post->post_title; ?></a>
                    </li>
                  <?php
                  }
                } else {
                  if ($meta['cm_type'] == 1) {
                  ?>
                    <li class="cm-list__item">
                      <span class="cm-checked"></span>
                      <a href="javascript:void(0);" data-post="<?php echo $post->ID; ?>" class="cm-list__link"><?php echo $post->post_title; ?></a>
                    </li>
                <?php
                  }
                }
                ?>
          <?php
              }
            }
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</div>