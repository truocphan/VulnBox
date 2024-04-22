"use strict";

(function ($) {
  /**
   * @var stm_lms_add_students
   */

  var courses = [];
  var emails = [];
  $(document).ready(function () {
    $('.add_students .btn').on('click', function (e) {
      e.preventDefault();
      var $this = $(this);
      gatherCourses();
      gatherEmail();
      if (!courses.length) {
        alert(stm_lms_add_students.translations['choose_course']);
        return false;
      }
      if (!emails.length) {
        alert(stm_lms_add_students.translations['choose_students']);
        return false;
      }
      var $notice = $('.add_students_notice .stm-lms-message');
      $.ajax({
        url: stm_lms_ajaxurl,
        type: 'POST',
        context: this,
        data: {
          courses: courses,
          emails: emails,
          action: 'stm_lms_add_student_manually',
          nonce: stm_lms_nonces['stm_lms_add_student_manually']
        },
        beforeSend: function beforeSend() {
          $this.addClass('loading');
          $notice.slideUp();
        },
        complete: function complete(data) {
          data = data.responseJSON;
          $this.removeClass('loading');
          $notice.slideDown().text(data.message);
        }
      });
    });
  });
  function gatherCourses() {
    courses = [];
    $('.stm_lms_my_bundle__selected_courses__single').each(function () {
      var $this = $(this);
      courses.push($this.attr('data-id'));
    });
  }
  function gatherEmail() {
    emails = [];
    $('.stm_lms_ent_groups_add_edit__email span').each(function () {
      var $this = $(this);
      emails.push($this.text());
    });
  }
})(jQuery);