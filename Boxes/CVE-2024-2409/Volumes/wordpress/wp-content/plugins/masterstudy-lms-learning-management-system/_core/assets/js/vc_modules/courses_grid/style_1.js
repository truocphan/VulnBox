"use strict";

(function ($) {
  $(document).ready(function () {
    var $sort = $('.stm_lms_courses_grid__sort select');
    var $container = $sort.closest('.stm_lms_courses_grid');
    var $grid = $container.find('.stm_lms_courses__grid');
    var $btn = $container.find('.stm_lms_load_more_courses');
    var offset = 0;
    var template = $btn.attr('data-template');
    var args = $btn.attr('data-args');
    $sort.on('change', function (e) {
      var sort_value = $sort.val();
      $btn.attr('data-args', args.replace('}', ',"sort":"' + sort_value + '"}'));
      if ($btn.hasClass('loading')) return false;
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          offset: offset,
          template: template,
          sort: sort_value,
          args: args,
          action: 'stm_lms_load_content',
          nonce: stm_lms_nonces['load_content']
        },
        beforeSend: function beforeSend() {
          $btn.addClass('loading');
          $container.addClass('loading');
        },
        complete: function complete(data) {
          data = data['responseJSON'];
          $btn.removeClass('loading');
          $container.removeClass('loading');
          $btn.closest('.stm_lms_courses').find('[data-pages]').html(data['content']);
          $btn.attr('data-offset', 1);
          hide_button($btn, 1);
        }
      });
    });
  });
})(jQuery);