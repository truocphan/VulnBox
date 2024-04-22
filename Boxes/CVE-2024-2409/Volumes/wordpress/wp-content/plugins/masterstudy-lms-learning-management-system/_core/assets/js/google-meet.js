"use strict";

(function ($) {
  var zeros = 0;
  $(window).on('load', function () {
    $('html').addClass('meet_lesson');
    timer();
  });
  var currentTab = 0;
  showTab(currentTab);
  var countSteps = $('#stepsss span.active').index() + 1;
  var OAuthUrl = '';
  function showTab(n) {
    if (currentTab === 2) {
      $(".gm-next-btn").prop("disabled", true);
      $(".gm-next-btn").css('background-color', '#cacaca');
    } else {
      $(".gm-next-btn").prop("disabled", false);
    }
    var x = $(".tab");
    x.eq(n).css("display", "block");
    if (n == 0) {
      $("#prevBtn").css("opacity", "0");
    } else {
      $("#prevBtn").css("opacity", "1");
    }
    if (n == x.length - 1 || n == x.length) {
      $(".gm-prev-btn").css('background-color', '');
      $(".gm-prev-btn").css('cursor', 'pointer');
      $(".gm-prev-btn").css('color', 'auto');
      $(".gm-prev-btn").css('border-color', '');
      $('#prevBtn').prop('disabled', false);
      $("#nextBtn").html("Grant Permissions");
      $("#prevBtn").html("Reset credential");
    } else {
      $("#nextBtn").html("Next");
      $("#prevBtn").html("Back");
    }
    fixStepIndicator(n);
  }
  $('.cancel-uploaded-file').on('click', function () {
    $(".gm-json-config-upload label").css("width", "101%");
    $("#lms-gm-upload-file").val("");
    $("#lms-gm-upload-file-label").text("Select File");
    $(".cancel-uploaded-file").css("opacity", "0");
    $(".gm-json-config-upload").css("width", "auto");
    $(".gm-json-config-upload").css("padding", "0");
    $(".gm-next-btn").css('background-color', '#cacaca');
    $(".gm-next-btn").css('cursor', 'default');
    $('#nextBtn').prop('disabled', true);
    var formData = new FormData();
    formData.append('action', 'gm_front_reset_settings_ajax');
    formData.append('nonce', stm_gm_front_ajax_variable.nonce);
    $.ajax({
      url: stm_gm_front_ajax_variable.url,
      type: 'post',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function success(response) {
        location.reload();
      },
      error: function error(xhr, ajaxOptions, thrownError) {
        console.log(xhr);
      }
    });
  });
  $('#prevBtn').on('click', function () {
    if (currentTab + 1 >= $(".tab").length) {
      if (confirm('Are you sure you want to delete this permanently from the site? Please confirm your choice?')) {
        $(".tab").css('display', 'none');
        $("#lms-gm-upload-file").val("");
        $("#lms-gm-upload-file-label").text("Select File");
        $(".cancel-uploaded-file").css("opacity", "0");
        $(".gm-json-config-upload").css("width", "auto");
        $(".gm-json-config-upload label").css("width", "auto");
        $(".gm-json-config-upload label").css("border", "none");
        $(".gm-json-config-upload label").css("margin-bottom", "0");
        $(".gm-json-config-upload").css("padding", "0");
        $(".gm-json-config-upload").css("border-radius", "4px");
        $('#nextBtn').prop('disabled', true);
        currentTab = 0;
        showTab(currentTab);
      } else {
        console.log('Not saved to the database.');
      }
    } else {
      nextPrev(-1);
    }
    $('#nextBtn').prop('disabled', false);
    $('#nextBtn').css('background-color', '');
    countSteps = $('#stepsss span.active').index() + 1;
  });
  $('#lms-gm-upload-file').on('change', function (e) {
    var credentialJson = $('#lms-gm-upload-file')[0].files[0];
    var formData = new FormData();
    formData.append('file', credentialJson);
    formData.append('action', 'gm_upload_credentials_ajax');
    formData.append('nonce', stm_google_meet_ajax_variable.nonce);
    formData.append('isFront', true);
    $.ajax({
      url: stm_google_meet_ajax_variable.url,
      type: 'post',
      data: formData,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function success(response) {
        $("#lms-gm-upload-file-label").text(credentialJson['name']);
        $(".gm-json-config-upload").addClass("uploaded-json");
        $('.gm-json-config-upload label').css('width', '97%');
        $('.gm-json-config-upload').css('width', '97%');
        $('.gm-json-config-upload').css('border-radius', '4px');
        $('.gm-json-config-upload label').css('border', 'none');
        $('.gm-json-config-upload ').css('padding', '12px');
        $('.cancel-uploaded-file').css('opacity', '1');
        $(".gm-json-config-upload label").css("margin-bottom", "0");
        $(".gm-prev-btn").css('background-color', '#cacaca');
        $(".gm-prev-btn").css('cursor', 'default');
        $(".gm-prev-btn").css('border-color', '#cacaca');
        $(".gm-prev-btn").css('color', '#fff');
        $('#prevBtn').prop('disabled', true);
        OAuthUrl = response.url;
        $(".gm-next-btn").prop("disabled", false);
        $(".gm-next-btn").css('background-color', '');
      },
      error: function error(xhr, ajaxOptions, thrownError) {
        console.log(xhr);
      }
    });
  });
  $('.lms-gm-btn-copy').on('click', function () {
    var input = $(this).siblings('#gm-copy-url');
    var value = input.val();
    var tempInput = $('<input>');
    $('body').append(tempInput);
    tempInput.val(value).select();
    document.execCommand('copy');
    tempInput.remove();
    $('.lms-gm-btn-copy').text('Copied');
  });
  $('#nextBtn').on('click', function () {
    nextPrev(1);
    countSteps = $('#stepsss span.active').index() + 1;
    $(".gm-prev-btn").css('color', '');
  });
  function nextPrev(n) {
    var x = $(".tab");
    if (currentTab !== 3) {
      x.eq(currentTab).css("display", "none");
    }
    currentTab = currentTab + n;
    if (currentTab >= x.length) {
      window.location.href = OAuthUrl;
    }
    showTab(currentTab);
  }
  function fixStepIndicator(n) {
    var x = $(".step");
    x.removeClass("active");
    x.eq(n).addClass("active");
  }
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

  // front after integrated state
  $('.gmi-tabs-container .tabs-nav li:first-child').addClass('active');
  $('.gmi-tabs-container .tab-pane:first-child').addClass('active');
  $('.gmi-tabs-container .tabs-nav li').on('click', function () {
    var tabId = $(this).find('a').attr('href');
    $('.gmi-tabs-container .tabs-nav li').removeClass('active');
    $(this).addClass('active');
    $('.gmi-tabs-container .tab-pane').removeClass('active');
    $(tabId).addClass('active');
  });
})(jQuery);