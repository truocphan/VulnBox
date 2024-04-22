"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
(function ($) {
  $(document).ready(function () {
    var attachmentIDs = [],
      mediaStreams = [],
      disableBtnAction = false,
      audioCounter = 1,
      videoCounter = 1;
    var apiEndpoint = ms_lms_resturl + '/media',
      messages = media_data.message,
      attachFileBtn = $('[data-id="masterstudy-file-upload-field"]'),
      recordAudioBtn = $('[data-id="masterstudy-audio-recorder"]'),
      recordVideoBtn = $('[data-id="masterstudy-video-recorder"]'),
      alertPopup = $("[data-id='assignment_file_alert']"),
      submitBtn = $('.masterstudy-button[data-id="masterstudy-review-submit"]');
    $('body').addClass('masterstudy-user-assignment__single');

    // On delete call alert
    $(document).on('click', '.masterstudy-file-attachment__delete', function (event) {
      event.preventDefault();
      alertPopup.addClass('masterstudy-alert_open');
      deleteAttachment(this, $(this).data('id'));
    });

    // Сancel alert for delete file
    alertPopup.find("[data-id='cancel']").click(closeAlertPopup);
    alertPopup.find('.masterstudy-alert__header-close').click(closeAlertPopup);
    function closeAlertPopup(e) {
      e.preventDefault();
      alertPopup.removeClass('masterstudy-alert_open');
    }

    // Audio Player for audio recorder
    MasterstudyAudioPlayer.init({
      selector: '.masterstudy-audio-player',
      showDeleteButton: false
    });
    recordAudioBtn.on('click', function (e) {
      e.preventDefault();
      if ($(this).hasClass('masterstudy-button_disabled')) {
        return;
      }
      var uploaded_files = upload_files_number();
      if (media_data.files_max_number * 1 > 0 && uploaded_files >= media_data.files_max_number * 1) {
        messageHandler(messages.file.number_error, 'error');
        return;
      }
      var audioRecorder = new MasterstudyAudioRecorder('.masterstudy-audio__recorder', {
        isHidden: true,
        directRecording: true,
        darkMode: media_data.dark_mode
      });
      var recorder = $('.masterstudy-audio__recorder');
      audioRecorder.startRecording().then(function (isAllowed) {
        disableBtnAction = !isAllowed;
        if (isAllowed) {
          recorder.removeClass('masterstudy-audio__recorder_hidden');
        } else {
          messageHandler(messages.audio.permission, 'error');
          switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
        }
      });
      switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
      if (disableBtnAction) return false;
      $('.masterstudy-message[data-id="message-box"]').addClass('masterstudy-message_hidden');
      disableBtnAction = true;
      var progressBar = $('.masterstudy-attachment-media .masterstudy-progress');
      progressBar.find('.masterstudy-progress__bar-filled').css('width', '0%');
      audioRecorder.addAction('beforeStop', function (recorder) {
        recorder.hideRecorder();
        progressBar.removeClass('masterstudy-progress_hidden');
      });
      audioRecorder.addAction('onStop', function (audioBlob, mediaStream, mediaRecorder) {
        disableBtnAction = false;
        if (!mediaStream || mediaStreams.indexOf(mediaStream.id) !== -1) return;
        mediaStreams.push(mediaStream.id);
        var uniqID = mediaStream.id.slice(-6) + audioCounter,
          fileUrl = window.URL.createObjectURL(audioBlob),
          postID = media_data.assignment_id,
          fileName = "audio-attachment-".concat(postID, "-").concat(uniqID, ".mp3"),
          audioFile = new File([audioBlob], fileName, {
            type: audioBlob.type
          });
        audioCounter++;
        var formData = new FormData();
        formData.append('file', audioFile);
        recorder.addClass('masterstudy-audio__recorder_hidden');
        if (audioBlob.size) {
          var sizeInMB = audioBlob.size / (1024 * 1024);
          if (sizeInMB > media_data.audio_max_size * 1) {
            messageHandler(messages.audio.size_error, 'error', messages.audio.download, fileUrl);
            switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
            return;
          }
        }
        $.ajax({
          url: apiEndpoint,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          xhr: function xhr() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
              if (evt.lengthComputable) {
                var currentPercent = Math.round(evt.loaded / evt.total * 100);
                currentPercent = currentPercent >= 95 ? 95 : currentPercent;
                // Update progress bar heres
                progressBar.find('.masterstudy-progress__percent').text(currentPercent);
                progressBar.find('.masterstudy-progress__bar-filled').css('width', currentPercent + '%');
              }
            }, false);
            return xhr;
          },
          headers: {
            'X-WP-Nonce': ms_lms_nonce,
            'Accept': 'application/json'
          },
          error: function error(xhr, status, _error) {
            messageHandler(messages.error.text, 'error', messages.audio.download, fileUrl);
            switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
          },
          complete: function complete(xhr, status) {
            if ('success' === status) {
              setTimeout(function () {
                progressBar.find('.masterstudy-progress__percent').text(100);
                progressBar.find('.masterstudy-progress__bar-filled').css('width', '100%');
              }, 1000);
              setTimeout(function () {
                var attachment = xhr.responseJSON;
                progressBar.addClass('masterstudy-progress_hidden');
                if (attachment) {
                  if (attachment.id > 0 && attachmentIDs.indexOf(attachment.id) === -1) {
                    attachmentIDs.push(attachment.id);
                    addAttachmentToPost(attachment, true);
                  }
                }
              }, 1500);
            } else {
              messageHandler(messages.error.text, 'error', messages.audio.download, fileUrl);
              switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
            }
          }
        });
      });
    });
    recordVideoBtn.on('click', function (e) {
      e.preventDefault();
      if ($(this).hasClass('masterstudy-button_disabled')) {
        return;
      }
      var uploaded_files = upload_files_number();
      if (media_data.files_max_number * 1 > 0 && uploaded_files >= media_data.files_max_number * 1) {
        messageHandler(messages.file.number_error, 'error');
        return;
      }
      var videoRecorder = new MasterstudyVideoRecoder('.masterstudy-video__recorder', {
        isHidden: true,
        clearSource: true
      });
      videoRecorder.startRecording().then(function (isAllowed) {
        disableBtnAction = !isAllowed;
        if (isAllowed) {
          videoRecorder.showRecorder();
        } else {
          messageHandler(messages.video.permission, 'error');
          switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
        }
      });
      switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
      if (disableBtnAction) return false;
      $('.masterstudy-message[data-id="message-box"]').addClass('masterstudy-message_hidden');
      disableBtnAction = true;
      videoRecorder.addAction('onStop', function (videoBlob, mediaStream, mediaRecorder) {
        videoRecorder.hideRecorder();
        disableBtnAction = false;
        if (!mediaStream || mediaStreams.indexOf(mediaStream.id) !== -1) return;
        mediaStreams.push(mediaStream.id);
        var uniqID = mediaStream.id.slice(-6) + videoCounter,
          fileUrl = window.URL.createObjectURL(videoBlob),
          postID = media_data.assignment_id,
          fileName = "video-attachment-".concat(postID, "-").concat(uniqID, ".mp4"),
          videoFile = new File([videoBlob], fileName, {
            type: videoBlob.type
          });
        videoCounter++;
        var formData = new FormData();
        formData.append('file', videoFile);
        if (videoBlob.size) {
          var sizeInMB = videoBlob.size / (1024 * 1024);
          if (sizeInMB > media_data.video_max_size * 1) {
            messageHandler(messages.video.size_error, 'error', messages.video.download, fileUrl);
            switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
            return;
          }
        }
        var progressBar = $('.masterstudy-attachment-media .masterstudy-progress');
        progressBar.removeClass('masterstudy-progress_hidden');
        progressBar.find('.masterstudy-progress__bar-filled').css('width', '0%');
        $.ajax({
          url: apiEndpoint,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          xhr: function xhr() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
              if (evt.lengthComputable) {
                var currentPercent = Math.round(evt.loaded / evt.total * 100);
                currentPercent = currentPercent >= 95 ? 95 : currentPercent;
                // Update progress bar here
                progressBar.find('.masterstudy-progress__percent').text(currentPercent);
                progressBar.find('.masterstudy-progress__bar-filled').css('width', currentPercent + '%');
              }
            }, false);
            return xhr;
          },
          headers: {
            'X-WP-Nonce': ms_lms_nonce,
            'Accept': 'application/json'
          },
          error: function error(xhr, status, _error2) {
            messageHandler(messages.error.text, 'error', messages.video.download, fileUrl);
            switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
          },
          complete: function complete(xhr, status) {
            if ('success' === status) {
              setTimeout(function () {
                progressBar.find('.masterstudy-progress__percent').text(100);
                progressBar.find('.masterstudy-progress__bar-filled').css('width', '100%');
              }, 1000);
              setTimeout(function () {
                var attachment = xhr.responseJSON;
                progressBar.addClass('masterstudy-progress_hidden');
                if (attachment) {
                  if (attachment.id > 0 && attachmentIDs.indexOf(attachment.id) === -1) {
                    attachmentIDs.push(attachment.id);
                    addAttachmentToPost(attachment, true);
                  }
                }
              }, 1500);
            } else {
              messageHandler(messages.error.text, 'error', messages.video.download, fileUrl);
              switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
            }
          }
        });
      });
    });

    // File uploader
    attachFileBtn.on('click', function (e) {
      e.preventDefault();
      if ($(this).hasClass('masterstudy-button_disabled')) {
        return;
      }
      $('.masterstudy-file-upload__input').click();
    });

    // watch change of files in input
    $('.masterstudy-file-upload__input').on('change', function (e) {
      $('.masterstudy-message[data-id="message-box"]').addClass('masterstudy-message_hidden');
      var files = Array.from(e.target.files),
        allowedExtensions = media_data.files_extensions.split(',').map(function (ext) {
          return ext.trim().toLowerCase();
        });
      var uploaded_files = upload_files_number() + files.length;
      if (media_data.files_max_number * 1 > 0 && uploaded_files > media_data.files_max_number * 1) {
        messageHandler(messages.file.number_error, 'error');
        return;
      }
      if (files.length === 0) return;
      var progressBar = $('.masterstudy-attachment-media .masterstudy-progress');
      progressBar.removeClass('masterstudy-progress_hidden');
      progressBar.find('.masterstudy-progress__percent').text(0);
      progressBar.find('.masterstudy-progress__bar-filled').css('width', '0%');
      var totalFileSize = Array.from(files).reduce(function (acc, file) {
        return acc + file.size;
      }, 0);
      var uploadedSize = 0;
      files.forEach(function (file) {
        switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
        var fileExtension = file.name.split('.').pop().toLowerCase();
        if (media_data.files_max_size * 1 > 0 && file.size > media_data.files_max_size * 1 * 1024 * 1024) {
          messageHandler(messages.file.size_error, 'error');
          switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
          return;
        }
        if (!allowedExtensions.includes(fileExtension)) {
          messageHandler(messages.file.extension, 'error');
          switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
          return;
        }
        var formData = new FormData();
        formData.append('file', file);
        $.ajax({
          url: apiEndpoint,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          xhr: function xhr() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
              if (evt.lengthComputable && files.length === 1) {
                var progress = Math.round(evt.loaded / evt.total * 100);
                progressBar.find('.masterstudy-progress__percent').text(progress);
                progressBar.find('.masterstudy-progress__bar-filled').css('width', progress + '%');
              }
            });
            return xhr;
          },
          headers: {
            'X-WP-Nonce': ms_lms_nonce,
            'Accept': 'application/json'
          },
          success: function success(attachment) {
            if (attachment && attachment.id) {
              if (attachment.id > 0 && attachmentIDs.indexOf(attachment.id) === -1) {
                attachmentIDs.push(attachment.id);
                uploadedSize += file.size;
                var progress = files.length === 1 ? 100 : Math.round(uploadedSize / totalFileSize * 100);
                addAttachmentToPost(attachment, true, progress);
              }
            } else {
              messageHandler(messages.error.text, 'error');
              switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
            }
          },
          error: function error(xhr, status, _error3) {
            messageHandler(messages.error.text, 'error');
            switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
          }
        });
      });
    });
    var reviewStatus = $('.masterstudy-radio-buttons').find('input:checked').val();
    if (typeof reviewStatus === 'undefined' || reviewStatus === null) {
      submitBtn.addClass('masterstudy-button_disabled');
    }
    $.each($('.masterstudy-radio-buttons'), function (i, radio) {
      $(radio).on('click', function () {
        submitBtn.removeClass('masterstudy-button_disabled');
      });
    });

    // Submit editor text and status
    submitBtn.on('click', function (e) {
      e.preventDefault();
      if (disableBtnAction) return false;
      $('.masterstudy-message[data-id="message-box"]').addClass('masterstudy-message_hidden');
      var reviewComment = $('.masterstudy-wp-editor').find('textarea').val();
      reviewStatus = $('.masterstudy-radio-buttons').find('input:checked').val();
      if (typeof reviewStatus === 'undefined' || reviewStatus === null) {
        return false;
      }
      switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
      disableBtnAction = true;
      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
          action: 'stm_lms_assignment_student_answer',
          status: reviewStatus,
          review: reviewComment,
          nonce: ms_lms_nonce,
          assignment_id: media_data.assignment_id
        },
        success: function success(res) {
          if (res.success === true) {
            messageHandler(media_data.message.success.text);
          } else {
            messageHandler(media_data.message.error.text, 'error');
          }
          switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
          disableBtnAction = false;
        },
        error: function error(_error4) {
          messageHandler(media_data.message.error.text, 'error');
          switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
          disableBtnAction = false;
        }
      });
    });
    function messageHandler(message) {
      var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'success';
      var downloadText = arguments.length > 2 ? arguments[2] : undefined;
      var file_url = arguments.length > 3 ? arguments[3] : undefined;
      var messageBox = $('.masterstudy-message[data-id="message-box"]'),
        messageIcon = messageBox.find('.masterstudy-message__icon'),
        messageText = messageBox.find('.masterstudy-message__text'),
        messageLink = messageBox.find('.masterstudy-message__link'),
        progressBar = $('.masterstudy-attachment-media .masterstudy-progress');
      messageIcon.attr('class', '');
      if ('success' === type) {
        messageBox.removeClass('masterstudy-message_color-danger masterstudy-message_bg-danger');
        messageBox.addClass('masterstudy-message_color-success masterstudy-message_bg-success');
        messageIcon.addClass('masterstudy-message__icon stmlms-check');
      } else {
        $('.masterstudy-attachment-media .masterstudy-loader').hide();
        progressBar.addClass('masterstudy-progress_hidden');
        messageBox.removeClass('masterstudy-message_color-success masterstudy-message_bg-success');
        messageBox.addClass('masterstudy-message_color-danger masterstudy-message_bg-danger');
        messageIcon.addClass('masterstudy-message__icon stmlms-warning');
        if (downloadText) {
          messageLink.html("<span class=\"stmlms-download\"></span> ".concat(downloadText));
          messageLink.attr('href', file_url);
          messageLink.attr('download', 'assignment_review_media');
        }
      }
      messageText.html(message);
      $('.masterstudy-message[data-id="message-box"]').removeClass('masterstudy-message_hidden');
    }
    function upload_files_number() {
      var attachments = $('.masterstudy-attachment-media__materials').find('.masterstudy-file-attachment');
      return attachments ? attachments.length : 0;
    }
    function addAttachmentToPost(attachment, isCreated, progress) {
      isCreated = isCreated === null || isCreated === undefined ? false : isCreated;
      var course_id = 0;
      if (typeof assignments_data !== 'undefined' && assignments_data !== null) {
        course_id = assignments_data.course_id || 0;
      }
      $.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
          action: 'stm_lms_add_assignment_attachment',
          attachment: attachment,
          attachment_id: attachment.id,
          post_id: media_data.assignment_id,
          is_created: isCreated,
          course_id: course_id,
          is_review: media_data.is_review,
          nonce: ms_lms_nonce
        },
        success: function success(res) {
          if (res.success === true) {
            generateFileHtml(attachment, res.data);
            $('.masterstudy-attachment-media .masterstudy-loader').hide();
            switchButtonState([recordAudioBtn, attachFileBtn, recordVideoBtn, submitBtn]);
          }
        },
        complete: function complete() {
          if (progress && typeof progress === 'number') {
            var progressBar = $('.masterstudy-attachment-media .masterstudy-progress');
            var currentProgress = progressBar.find('.masterstudy-progress__percent').text();
            progress = currentProgress < progress ? progress : +currentProgress;
            progress = progress > 100 ? 100 : progress;
            progressBar.find('.masterstudy-progress__percent').text(progress);
            progressBar.find('.masterstudy-progress__bar-filled').css('width', "".concat(progress, "%"));
            if (progress === 100) {
              setTimeout(function () {
                progressBar.addClass('masterstudy-progress_hidden');
              }, 1500);
            }
          }
        }
      });
    }
    function getFileType(url, formats) {
      var fileExtension = url.split('.').pop();
      for (var fileType in formats) {
        if (formats[fileType].includes(fileExtension)) {
          return fileType;
        }
      }
      return 'unknown';
    }
    function deleteAttachment(deleteBtn, attachmentID) {
      alertPopup.find("[data-id='submit']").click(function (e) {
        e.preventDefault();
        $.ajax({
          type: 'POST',
          url: ajaxurl,
          data: {
            action: 'stm_lms_delete_assignment_attachment',
            attachment_id: attachmentID,
            post_id: media_data.assignment_id,
            nonce: ms_lms_nonce,
            is_review: media_data.is_review
          },
          beforeSend: function beforeSend() {
            alertPopup.removeClass('masterstudy-alert_open');
          },
          success: function success(res) {
            if (res.success === true) {
              $(deleteBtn).parents('.masterstudy-file-attachment').remove();
            }
          }
        });
      });
    }
    function switchButtonState(button, disableClass) {
      disableClass = disableClass || 'masterstudy-button_disabled';
      if (button instanceof NodeList || button instanceof HTMLCollection || Array.isArray(button)) {
        button.forEach(function (btn) {
          switchButtonState(btn);
        });
      } else {
        button.toggleClass(disableClass);
      }
    }
    function generateFileHtml(attachment, data) {
      var attachmentUrl = attachment.url || attachment.source_url,
        fileType = getFileType(attachmentUrl, data.files_formats),
        attachmentTitle = attachment.title;
      if (_typeof(attachmentTitle) === 'object') {
        attachmentTitle = attachment.title.raw;
      }
      var labeledFilesize = '';
      if (attachment.media_details) {
        var filesize = Math.round(attachment.media_details.filesize / 1024);
        var filesize_label = filesize > 1000 ? 'mb' : 'kb';
        filesize = filesize > 1000 ? Math.round(filesize / 1024) : filesize;
        labeledFilesize = filesize + ' ' + filesize_label;
      }
      if (attachment.filesizeHumanReadable) {
        labeledFilesize = attachment.filesizeHumanReadable.toLowerCase();
      }
      var attachmentTemplate = $('.masterstudy-attachment-media__actions [data-id="masterstudy-file-attachment__template"]').clone(),
        audioPlayer = attachmentTemplate.find(".masterstudy-audio-player"),
        videoPlayer = attachmentTemplate.find(".masterstudy-video__player");
      attachmentTemplate.find("img").attr("src", "".concat(data.icon_url + fileType, ".svg"));
      attachmentTemplate.find(".masterstudy-file-attachment__title").html(attachmentTitle);
      attachmentTemplate.find(".masterstudy-file-attachment__size").html(labeledFilesize);
      attachmentTemplate.find(".masterstudy-file-attachment__delete").attr("data-id", attachment.id);
      if (fileType === "audio") {
        audioPlayer.attr("data-id", "masterstudy-audio-player-".concat(attachment.id));
        audioPlayer.find("audio").attr("src", attachmentUrl);
        audioPlayer.find("source").attr("src", attachmentUrl);
        audioPlayer.find(".masterstudy-audio-player__download-link").attr("href", attachmentUrl);
        audioPlayer.removeClass("masterstudy-audio-player_hidden");
        videoPlayer.addClass("masterstudy-video__player--hidden");
      }
      if (fileType === "video") {
        videoPlayer.attr("src", attachmentUrl);
        videoPlayer.find("source").attr("src", attachmentUrl);
        videoPlayer.removeClass("masterstudy-video__player--hidden");
        audioPlayer.addClass("masterstudy-audio-player_hidden");
      }
      $(".masterstudy-attachment-media__materials").append(attachmentTemplate.prop("outerHTML"));
      if (fileType === "audio") {
        MasterstudyAudioPlayer.init({
          selector: "[data-id=\"masterstudy-audio-player-".concat(attachment.id, "\"]"),
          showDeleteButton: false
        });
      }
    }
  });
})(jQuery);