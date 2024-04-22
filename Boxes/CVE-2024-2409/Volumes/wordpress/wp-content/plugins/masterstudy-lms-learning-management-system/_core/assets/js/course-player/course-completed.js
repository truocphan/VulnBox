"use strict";

(function ($) {
  $(document).ready(function () {
    if (course_completed.completed) {
      $('.masterstudy-course-player-course-completed').show();
      $('body').addClass('masterstudy-course-player-course-completed_active');
      stmLmsInitProgress();
    }
    $('.masterstudy-course-player-course-completed').on('click', function (event) {
      if ($(event.target).hasClass('masterstudy-course-player-course-completed')) {
        $(this).hide();
        $('body').removeClass('masterstudy-course-player-course-completed_active');
      }
    });
    $('.masterstudy-course-player-course-completed__buttons, .masterstudy-course-player-course-completed__info-close').on('click', function (event) {
      $('.masterstudy-course-player-course-completed').hide();
      $('body').removeClass('masterstudy-course-player-course-completed_active');
    });
  });
  function stmLmsInitProgress() {
    var course_id = course_completed.course_id;
    var $statsContainer = $('#masterstudy-course-player-course-completed');
    var loading = true;
    var stats = {};
    var ajaxUrl = course_completed.ajax_url + '?action=stm_lms_total_progress&course_id=' + course_id + '&nonce=' + course_completed.nonce;
    $.get(ajaxUrl, function (response) {
      stats = response;
      loading = false;
      course_completed_success();
    });
    function course_completed_success() {
      $('.masterstudy-course-player-course-completed__info-loading').hide();
      $('.masterstudy-course-player-course-completed__info-success').show();
      $('.masterstudy-course-player-course-completed__opportunities-percent').html(stats.course.progress_percent + '%');
      if (stats.title) {
        $statsContainer.find('h2').show();
        $statsContainer.find('h2').html(stats.title);
      }
      if (stats.curriculum.hasOwnProperty('lesson')) {
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-lesson').addClass('show-item');
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-lesson .masterstudy-course-player-course-completed__curiculum-statistic-item_completed').html(stats.curriculum.lesson.completed);
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-lesson .masterstudy-course-player-course-completed__curiculum-statistic-item_total').html(stats.curriculum.lesson.total);
      }
      if (stats.curriculum.hasOwnProperty('multimedia')) {
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-multimedia').addClass('show-item');
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-multimedia .masterstudy-course-player-course-completed__curiculum-statistic-item_completed').html(stats.curriculum.multimedia.completed);
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-multimedia .masterstudy-course-player-course-completed__curiculum-statistic-item_total').html(stats.curriculum.multimedia.total);
      }
      if (stats.curriculum.hasOwnProperty('quiz')) {
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-quiz').addClass('show-item');
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-quiz .masterstudy-course-player-course-completed__curiculum-statistic-item_completed').html(stats.curriculum.quiz.completed);
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-quiz .masterstudy-course-player-course-completed__curiculum-statistic-item_total').html(stats.curriculum.quiz.total);
      }
      if (stats.curriculum.hasOwnProperty('assignment')) {
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-assignment').addClass('show-item');
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-assignment .masterstudy-course-player-course-completed__curiculum-statistic-item_completed').html(stats.curriculum.assignment.completed);
        $statsContainer.find('.masterstudy-course-player-course-completed__curiculum-statistic-item_type-assignment .masterstudy-course-player-course-completed__curiculum-statistic-item_total').html(stats.curriculum.assignment.total);
      }
      $('.masterstudy-button_course_button').attr('href', stats.url);
    }
  }
})(jQuery);