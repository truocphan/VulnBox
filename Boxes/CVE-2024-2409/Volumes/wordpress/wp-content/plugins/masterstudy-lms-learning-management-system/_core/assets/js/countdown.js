"use strict";

function startTimer(duration, display) {
  var seconds = duration;
  setInterval(function () {
    var days = Math.floor(seconds / 24 / 60 / 60);
    var hoursLeft = Math.floor(seconds - days * 86400);
    var hours = Math.floor(hoursLeft / 3600);
    var minutesLeft = Math.floor(hoursLeft - hours * 3600);
    var minutes = Math.floor(minutesLeft / 60);
    var remainingSeconds = seconds % 60;
    function pad(n) {
      return n < 10 ? "0" + n : n;
    }
    var display_text = "".concat(pad(hours), ":").concat(pad(minutes), ":").concat(pad(remainingSeconds));
    if (seconds === 0) {
      location.reload();
    } else {
      display.text("".concat(display_text));
    }
    seconds -= 1;
  }, 1000);
}
stmLmsStartTimers();
function stmLmsStartTimers() {
  jQuery(function ($) {
    $(document).ready(function () {
      $('[data-lms-timer]').each(function () {
        var $this = $(this);
        var timer = $this.data('lms-timer');
        startTimer(timer, $this);
      });
    });
  });
}