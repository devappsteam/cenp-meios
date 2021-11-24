<div class="cm-section cm-section--white">
  <div class="cm-container">
    <div class="cm-panel">
      <div class="cm-panel__header cm-center">
        <button type="button" class="cm-btn btn-accordion-open text-truncate"><?php echo $post_meta['cm_agency_title']; ?></button>
      </div>
      <div class="cm-panel__body" style="display: none;">
        <div class="cm-panel__columns">
          <?php
          $agencies_count = count($agencies);
          $agencies_pack = array_chunk($agencies, intval($agencies_count / 2));
          $legends = $post_meta['cm_legends'];
          foreach ($agencies_pack as $agency_item) {
          ?>
            <div class="cm-colum">
              <ul>
                <?php
                foreach ($agency_item as $agency) {
                  $tooltip = array();
                  foreach ($legends as  $legend) {
                    $items = explode(';', $legend['agencies']);
                    foreach ($items as $item) {
                      if (trim($agency['name']) == trim($item)) {
                        $tooltip = $legend;
                      }
                    }
                  }
                ?>
                  <li class="<?php echo !empty($tooltip['legend']) ? 'tooltip' : ''; ?>">
                    <?php echo $tooltip['indicator'] . ' ' . $agency['name']; ?>
                    <?php
                    if(!empty($tooltip['legend'])){
                    ?>
                      <span class="tooltiptext"><?php echo htmlentities($tooltip['legend']); ?></span>
                    <?php
                    }
                    ?>
                  </li>
                <?php
                }
                ?>
              </ul>
            </div>
          <?php
          }
          ?>
        </div>
        <div class="cm-panel-description">
          <?php echo $post_meta['cm_description_agency']; ?>
        </div>
      </div>
    </div>
  </div>
</div>