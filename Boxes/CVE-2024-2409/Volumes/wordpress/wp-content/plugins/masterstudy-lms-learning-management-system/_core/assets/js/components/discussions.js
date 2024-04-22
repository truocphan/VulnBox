"use strict";

(function ($) {
  $(document).ready(function () {
    var search_value = '';
    var currentOffset = 0;
    get_comments(currentOffset, false, search_value);

    // open "add comment" textarea
    $('[data-id="masterstudy-discussions-add-comment"]').click(function (e) {
      e.preventDefault();
      $(this).parent().hide(200);
      $(this).parent().prev().show(200);
    });

    // close "add comment" textarea
    $(document).on('click', '.masterstudy-discussions__cancel', function (e) {
      e.preventDefault();
      var _this = $(this);
      $(this).parent().hide(200);
      $(this).parent().next().show(200);
      $(this).parent().find('.masterstudy-discussions__textarea').val('');
      setTimeout(function () {
        _this.parent().find('.masterstudy-discussions__error').remove();
      }, 150);
      if ($(this).parent().parent().hasClass('masterstudy-discussions__reply')) {
        setTimeout(function () {
          _this.parent().remove();
        }, 400);
      }
    });

    // open reply "add comment" textarea
    $(document).on('click', '.masterstudy-discussions__reply-button', function () {
      var newHTML = "\n                <div class=\"masterstudy-discussions__send\" style=\"display:none;\">\n                    <div class=\"masterstudy-discussions__send-comment\">\n                        <textarea\n                            name=\"masterstudy-discussions-comment-textarea\"\n                            class=\"masterstudy-discussions__textarea\"\n                            placeholder=\"".concat(discussions_data.textarea_placeholder, "\"\n                            rows=\"3\"\n                        ></textarea>\n                        <div class=\"masterstudy-discussions__send-wrapper\">\n                            <button class=\"masterstudy-discussions__send-button\"></button>\n                        </div>\n                    </div>\n                    <button class=\"masterstudy-discussions__cancel\">\n                        ").concat(discussions_data.cancel_title, "\n                    </button>\n                </div>\n            ");
      var sendarea = $(this).closest('.masterstudy-discussions__comment').siblings().find('.masterstudy-discussions__send');
      sendarea.hide(200);
      setTimeout(function () {
        sendarea.remove();
      }, 200);
      $(this).closest('.masterstudy-discussions__comment').siblings().find('.masterstudy-discussions__reply-button').show(200);
      $(this).parent().prepend(newHTML);
      $(this).hide(200);
      $(this).parent().find('.masterstudy-discussions__send').show(200);
    });

    // open search input
    $('.masterstudy-discussions__search-button').click(function () {
      $(this).parent().hide(200);
      $(this).parent().prev().prev().show(150);
    });

    // close search input
    $('.masterstudy-discussions__search-close').click(function () {
      $(this).parent().parent().hide(150);
      $(this).parent().parent().next().next().show(200);
      $('#masterstudy-discussions-search').val('');
      search_value = '';
      currentOffset = 0;
      get_comments(0, false, search_value);
    });

    // search comments
    $(document).on('keydown', '#masterstudy-discussions-search', function (event) {
      if (event.keyCode === 13) {
        event.preventDefault();
        $('.masterstudy-discussions__search-add').addClass('masterstudy-discussions__search-add_loading');
        search_value = $(this).val();
        currentOffset = 0;
        get_comments(0, false, search_value);
      }
    });

    // search comments
    $(document).on('click', '.masterstudy-discussions__search-add', function () {
      $(this).addClass('masterstudy-discussions__search-add_loading');
      search_value = $('#masterstudy-discussions-search').val();
      currentOffset = 0;
      get_comments(0, false, search_value);
    });

    // load more comments
    $(document).on('click', '.masterstudy-discussions__load-more', function () {
      get_comments(currentOffset, true, search_value);
    });

    // add comment
    $(document).on('click', '.masterstudy-discussions__send-button', function () {
      var container = $(this).closest('.masterstudy-discussions__send'),
        comment = container.find('.masterstudy-discussions__textarea').val();
      if (comment.length > 0) {
        if (container.parent().hasClass('masterstudy-discussions__reply')) {
          $(this).addClass('masterstudy-discussions__send-button_loading');
          add_comment(comment, container.parent().parent().attr('id'));
        } else {
          add_comment(comment, 0);
        }
      }
    });

    // get all comments
    function get_comments(offset, byclick, search) {
      $.ajax({
        url: discussions_data.ajax_url,
        dataType: 'json',
        data: {
          'action': 'stm_lms_get_comments',
          'nonce': discussions_data.get_nonce,
          'post_id': discussions_data.lesson_id,
          'offset': offset,
          'search': search
        },
        beforeSend: function beforeSend() {
          $('.masterstudy-discussions__load-more').addClass('masterstudy-discussions__load-more_loading');
        },
        success: function success(data) {
          var commentsHtml = '',
            commentsContainer = $('.masterstudy-discussions__content');
          if (data.posts.length > 0) {
            $('.masterstudy-discussions').removeClass('masterstudy-discussions_not-items');
            data.posts.forEach(function (comment) {
              commentsHtml += generateCommentHtml(comment, false);
              if (comment.replies.length > 0) {
                commentsHtml += getCommentsReply(comment.replies);
              }
            });
            commentsContainer.html(byclick ? commentsContainer.html() + commentsHtml : commentsHtml);
          } else {
            if (search.length > 0) {
              data.message = generateNotFoundMessage(data.message);
              commentsContainer.html(data.message);
              $('.masterstudy-discussions').removeClass('masterstudy-discussions_not-items');
            } else {
              $('.masterstudy-discussions').addClass('masterstudy-discussions_not-items');
              var not_items = generateNotItems();
              commentsContainer.html(not_items);
            }
          }
          if (data.navigation) {
            currentOffset++;
            $('.masterstudy-discussions__navigation').addClass('masterstudy-discussions__navigation_show');
          } else {
            $('.masterstudy-discussions__navigation').removeClass('masterstudy-discussions__navigation_show');
          }
          $('.masterstudy-discussions__search-add').removeClass('masterstudy-discussions__search-add_loading');
          $('.masterstudy-discussions__load-more').removeClass('masterstudy-discussions__load-more_loading');
        }
      });
    }

    // add user's comment
    function add_comment(comment, parent) {
      var container = $('.masterstudy-discussions__header .masterstudy-discussions__send');
      $.ajax({
        url: discussions_data.ajax_url,
        dataType: 'json',
        data: {
          'action': 'stm_lms_add_comment',
          'nonce': discussions_data.add_nonce,
          'post_id': discussions_data.lesson_id,
          'course_id': discussions_data.course_id,
          'comment': comment,
          'parent': parent
        },
        beforeSend: function beforeSend() {
          if (parent === 0) {
            container.find('.masterstudy-discussions__send-button').addClass('masterstudy-discussions__send-button_loading');
            container.find('.masterstudy-discussions__error').remove();
          }
        },
        success: function success(data) {
          if (data.status === 'error') {
            data.message = generateErrorHtml(data.message);
            $('.masterstudy-discussions__send-comment').after(data.message);
            container.find('.masterstudy-discussions__send-button').removeClass('masterstudy-discussions__send-button_loading');
          } else if (Object.keys(data.comment).length > 0) {
            search_value = '';
            currentOffset = 0;
            get_comments(0, false, search_value);
            if (parent === 0) {
              container.find('.masterstudy-discussions__textarea').val('');
              container.hide(200);
              container.next().show(200);
              container.find('.masterstudy-discussions__send-button').removeClass('masterstudy-discussions__send-button_loading');
            }
          }
        }
      });
    }

    // generate html for error
    function generateErrorHtml(message) {
      return "\n            <div class=\"masterstudy-discussions__error\">\n                ".concat(message, "\n            </div>\n            ");
    }
    // generate html for comments
    function generateCommentHtml(comment, isReply) {
      var commentClass = 'masterstudy-discussions__comment',
        roleHtml = '';
      if (isReply) {
        commentClass += ' masterstudy-discussions__comment_reply';
      }
      if (comment.author.is_instructor.length > 0) {
        roleHtml = "<span class=\"masterstudy-discussions__role\">".concat(comment.author.is_instructor, "</span>");
      }
      return "\n                <div class=\"".concat(commentClass, "\">\n                    <div class=\"masterstudy-discussions__avatar\">\n                        <img src=\"").concat(comment.author.avatar_url, "\" class=\"masterstudy-discussions__avatar-image\">\n                    </div>\n                    <div class=\"masterstudy-discussions__main\">\n                        <div class=\"masterstudy-discussions__user\">\n                            <div class=\"masterstudy-discussions__wrapper\">\n                                <span class=\"masterstudy-discussions__name\">").concat(comment.author.login, "</span>\n                                ").concat(roleHtml, "\n                            </div>\n                            <div class=\"masterstudy-discussions__time\">").concat(comment.datetime, "</div>\n                        </div>\n                        <div class=\"masterstudy-discussions__message\" id=\"").concat(comment.comment_ID, "\">\n                            <div class=\"masterstudy-discussions__text\">").concat(comment.content, "</div>\n                            <div class=\"masterstudy-discussions__reply\">\n                                <span class=\"masterstudy-discussions__reply-button\">").concat(discussions_data.reply_title, "</span>\n                            </div>\n                        </div>\n                    </div>\n                </div>");
    }

    // generate message if not found comments
    function generateNotFoundMessage(message) {
      return "<div class=\"masterstudy-discussions__not-found\">".concat(message, "</div>");
    }

    // generate message if not comments yet
    function generateNotItems() {
      return "\n                <div class=\"masterstudy-discussions__not-items\">\n                    <span class=\"masterstudy-discussions__not-items-icon\"></span>\n                    <span class=\"masterstudy-discussions__not-items-title\">\n                        ".concat(discussions_data.not_items_title, "\n                    </span>\n                    <span class=\"masterstudy-discussions__not-items-subtitle\">\n                        ").concat(discussions_data.not_items_subtitle, "\n                    </span>\n                </div>");
    }

    // get all comment's replies to chat
    function getCommentsReply(comments) {
      var commentsHtml = '';
      comments.forEach(function (comment) {
        commentsHtml += generateCommentHtml(comment, true);
        if (comment.replies.length > 0) {
          commentsHtml += getCommentsReply(comment.replies);
        }
      });
      return commentsHtml;
    }
  });
})(jQuery);