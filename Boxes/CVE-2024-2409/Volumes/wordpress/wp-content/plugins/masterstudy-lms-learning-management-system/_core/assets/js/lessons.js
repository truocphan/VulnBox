"use strict";

(function ($) {
  $(document).ready(function () {
    if ($('.stm_lms_complete_lesson').hasClass('completed')) {
      $(".stm-lms-lesson_navigation ").addClass('completed');
    }
    $('.stm_lms_complete_lesson').on('click', function (e) {
      var disabled = $(this).closest('.stm-lms-lesson_navigation_complete').attr('data-disabled');
      if (disabled === 'true') return false;
      e.preventDefault();
      if ($(this).hasClass('completed')) return false;
      var course = $(this).data('course');
      var lesson = $(this).data('lesson');
      $.ajax({
        url: stm_lms_ajaxurl,
        dataType: 'json',
        context: this,
        data: {
          course: course,
          lesson: lesson,
          action: 'stm_lms_complete_lesson',
          nonce: stm_lms_nonces['stm_lms_complete_lesson']
        },
        beforeSend: function beforeSend() {
          $(this).addClass('loading');
        },
        complete: function complete(data) {
          var data = data['responseJSON'];
          $(this).removeClass('loading');
          var hasComplete = $(this).closest('[data-completed]').attr('data-completed');
          var $button = $('.stm_lms_complete_lesson[data-course="' + data.course_id + '"][data-lesson="' + data.lesson_id + '"]');
          if (typeof hasComplete !== 'undefined') {
            $(this).closest('[data-completed]').removeClass('uncompleted').addClass('completed');
            $button.find('span').text(hasComplete);
            stmLmsExternalInitProgress();
          }
          $button.addClass('completed');
        }
      });
    });
    $(document).ready(function () {
      var $videoFrames = $(".stm_lms_video iframe");
      function resizeVideoFrames() {
        $videoFrames.each(function () {
          var width = $(this).width();
          $(this).css({
            height: width / 1.7777 + "px",
            "min-height": width / 1.7777 + "px"
          });
        });
      }

      // Initial resize
      resizeVideoFrames();

      // Resize on window resize
      $(window).resize(resizeVideoFrames);
    });
    $('.stm_lms_video').on('click', function () {
      var $this = $(this);
      $this.addClass('visible');
      if ($this.hasClass('stm_lms_video__iframe')) {
        var $iframe = $this.find('iframe');
        $iframe.attr('src', $iframe.attr('data-src'));
      }
    });
    if ($('#stm_lms_video').length > 0) {
      var options = {
        currentTime: 3
      };
      var videoId = $('#stm_lms_video').attr('data-id');
      var lastTime = 0;
      if (typeof localStorage.getItem('video-' + videoId) !== 'undefined') {
        lastTime = localStorage.getItem('video-' + videoId);
      }
      var player = videojs('stm_lms_video', options, function onPlayerReady() {
        this.currentTime(lastTime);
        this.on('timeupdate', function () {
          localStorage.setItem('video-' + videoId, this.currentTime());
        });
      });
    }
  });
})(jQuery);