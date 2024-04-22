"use strict";

(function ($) {
  $(document).ready(function () {
    // remove link on safari
    if (isSafari()) {
      $('.masterstudy-course-player-lesson-materials__link').remove();
    }

    // link for download all materials
    $('.masterstudy-course-player-lesson-materials__link').click(handleDownloadClick);

    // Audio Player init
    if (typeof MasterstudyAudioPlayer !== 'undefined') {
      MasterstudyAudioPlayer.init({
        selector: '.masterstudy-audio-player',
        showDeleteButton: false
      });
    }
    function handleDownloadClick() {
      $('.masterstudy-course-player-lesson-materials').find('.masterstudy-file-attachment__link').each(function () {
        var clickEvent = new MouseEvent('click', {
          bubbles: true,
          cancelable: true,
          view: window
        });
        this.dispatchEvent(clickEvent);
      });
    }
  });
  function isSafari() {
    return /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
  }
})(jQuery);