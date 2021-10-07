(function ($) {
  'use strict';

  $(function () {
    $('.cenp-main-wrapper').removeClass('loading');

    $(document).on('click', '.cenp-post-item', function (event) {
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
          $('.cenp-main-wrapper').addClass('loading');
        },
        success: function (response) {
          $('.cenp-main-wrapper').removeClass('loading');
          $('.cenp-main-wrapper').html(response);

        },
        error: function () {
          $('.cenp-main-wrapper').removeClass('loading');
        }
      });
    });

  });

})(jQuery);