(function ($) {
  'use strict';

  $(function () {
    $('.cm-main').removeClass('loading');

    $(document).on('click', '.cm-list__link', function (event) {
      event.preventDefault();
      $('body').removeClass('cm-modal-show');
      var post = parseInt($(this).data('post'));

      $.ajax({
        url: cenp_obj.ajax_url,
        type: "POST",
        dataType: "HTML",
        data: {
          post: post,
          action: 'cm_find_post_by_id'
        },
        beforeSend: function () {
          $('.cm-main').addClass('loading');
          $('#cm_panels_wrapper').html(null);
        },
        success: function (response) {
          $('.cm-main').removeClass('loading');
          $('#cm_panels_wrapper').html(response);
        },
        error: function () {
          $('.cm-main').removeClass('loading');
        }
      });
    });
	  
    var sidebar = jQuery('.cm-main').offset().top;
    $(window).scroll(function (event) {
      var scroll = $(window).scrollTop();
      if (scroll > sidebar) {
        $('.cm-sidebar-wrapper').prop('style', 'position: fixed; top: 50px; width: 224px;');
      } else {
        $('.cm-sidebar-wrapper').prop('style', '');
      }
    });
  });

  $(document).on('click', '#cm_meios', function (e) {
    e.preventDefault();
    $('body').addClass('cm-modal-show');
  });

  $(document).on('click', '[data-button="close"]', function (e) {
    e.preventDefault();
    $('body').removeClass('cm-modal-show');
  });

  $(document).on('click', '.btn-accordion-open', function () {
    if ($('.cm-panel__body').is(':visible')) {
      $('.cm-panel__body').slideUp();
    } else {
      $('.cm-panel__body').slideDown();
    }
  });

  $(document).on('click', '.cm-btn', function () {
    if ($(this).data('href').length > 0) {
      window.location.href = $(this).data('href');
    }
  });
	
  $(document).ready(function(){
	  jQuery('.cm-list__link').first().click();
  });

})(jQuery);