(function ($) {
  'use strict';

  $(function () {
    $('.cm-main').removeClass('loading');

    $(document).on('click', '.cm-item', function (event) {
      event.preventDefault();

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
        },
        success: function (response) {
          $('.cm-main').removeClass('loading');
          $('.cm-content').html(response)
        },
        error: function () {
          $('.cm-main').removeClass('loading');
        }
      });
    });
    jQuery('.cm-category').first().find('.cm-item').first().click();

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
})(jQuery);