"use strict";

(function ($) {
  var zeros = 0;
  $(window).on('load', function () {
    var $stream_window = $('.stm_lms_stream_lesson');
    var $stream_window_height = $stream_window.outerHeight();
    $('.stm_lms_stream_lesson__content, .stm_lms_stream_lesson .left').resizable({
      start: function start() {
        $('.stm_lms_stream_lesson').addClass('is-resizing');
      },
      stop: function stop() {
        $('.stm_lms_stream_lesson').removeClass('is-resizing');
      },
      minHeight: 100,
      minWidth: 200,
      maxHeight: $stream_window_height - 11
    });
    timer();
    var $stream_not_ended = $('.stream-is-not-ended');
    if ($stream_not_ended.length) {
      setTimeout(function () {
        $('.stream-cannot-be-completed').removeClass('stream-cannot-be-completed stream-is-not-ended').attr('data-disabled', false);
      }, $stream_not_ended.data('timer') * 1000);
    }
  });
  function timer() {
    var $timer = $('.stm_countdown');
    if (!$timer.length) return false;
    var flash = false;
    var ts = $timer.data('timer');
    $timer.countdown({
      timestamp: ts,
      callback: function callback(days, hours, minutes, seconds) {
        var summaryTime = days + hours + minutes + seconds;
        if (summaryTime === 0) {
          zeros++;
        }
        if (zeros === 3) {
          window.location.reload(false);
        }
      }
    });
  }
})(jQuery);