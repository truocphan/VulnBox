"use strict";

(function ($) {
  $(document).ready(function () {
    // close curriculum if mobile or tablet
    if (window.matchMedia('(max-width: 1024px)').matches) {
      if (localStorage.getItem('curriculum_open') === 'yes') {
        localStorage.removeItem('curriculum_open');
      }
    }
    var currentUrl = window.location.href;
    var url = new URL(currentUrl);
    // open-discussions sidebar by get request
    if ($('.masterstudy-course-player-discussions__wrapper').length) {
      if (url.searchParams.has('discussions_open') || localStorage.getItem('discussions_open') === 'yes') {
        $('.masterstudy-course-player-header__discussions').addClass('masterstudy-course-player-header__discussions_open');
        if (window.matchMedia('(max-width: 1024px)').matches) {
          $('.masterstudy-course-player-discussions').find('.masterstudy-course-player-quiz__navigation-tabs').addClass('masterstudy-course-player-quiz__navigation-tabs_show');
        }
        $('.masterstudy-course-player-discussions').addClass('masterstudy-course-player-discussions_open');
        $('body').addClass('masterstudy-course-player-body-hidden');
        if (!$('.masterstudy-course-player-curriculum').hasClass('masterstudy-course-player-curriculum_open')) {
          $('.masterstudy-course-player-content').addClass('masterstudy-course-player-content_open-sidebar');
        }
      }
    } else {
      localStorage.removeItem('discussions_open');
      url.searchParams["delete"]('discussions_open');
      history.pushState({}, '', url.toString());
    }
    // open-curriculum sidebar by get request
    if (url.searchParams.has('curriculum_open') || localStorage.getItem('curriculum_open') === 'yes') {
      $('[data-id="masterstudy-curriculum-switcher"]').addClass('masterstudy-switch-button_active');
      $('.masterstudy-course-player-curriculum').addClass('masterstudy-course-player-curriculum_open');
      $('body').addClass('masterstudy-course-player-body-hidden');
      if (!$('.masterstudy-course-player-discussions').hasClass('masterstudy-course-player-discussions_open')) {
        $('.masterstudy-course-player-content').addClass('masterstudy-course-player-content_open-sidebar');
      }
      if (window.matchMedia('(max-width: 1366px)').matches) {
        $('.masterstudy-course-player-discussions').removeClass('masterstudy-course-player-discussions_open');
        $('.masterstudy-course-player-header__discussions').removeClass('masterstudy-course-player-header__discussions_open');
        if (url.searchParams.has('discussions_open')) {
          url.searchParams["delete"]('discussions_open');
          history.pushState({}, '', url.toString());
        }
      }
    }

    // open-close discussions sidebar
    $('.masterstudy-course-player-header__discussions').click(function () {
      var currentUrl = window.location.href;
      var url = new URL(currentUrl);
      if (url.searchParams.has('discussions_open')) {
        url.searchParams["delete"]('discussions_open');
      } else if (localStorage.getItem('discussions_open') !== 'yes') {
        url.searchParams.set('discussions_open', 'yes');
      }
      history.pushState({}, '', url.toString());
      if (localStorage.getItem('discussions_open') === 'yes') {
        localStorage.removeItem('discussions_open');
      } else {
        localStorage.setItem('discussions_open', 'yes');
      }
      if ($(this).hasClass('masterstudy-course-player-header__discussions_open')) {
        $(this).css({
          'transition': '0.3s',
          'transition-timing-function': 'linear'
        });
      } else {
        $(this).css({
          'transition': '1.25s',
          'transition-timing-function': 'linear'
        });
      }
      $(this).toggleClass('masterstudy-course-player-header__discussions_open');
      if (window.matchMedia('(max-width: 1024px)').matches) {
        $('.masterstudy-course-player-discussions').find('.masterstudy-course-player-quiz__navigation-tabs').toggleClass('masterstudy-course-player-quiz__navigation-tabs_show');
      }
      $('.masterstudy-course-player-discussions').toggleClass('masterstudy-course-player-discussions_open');
      $('body').toggleClass('masterstudy-course-player-body-hidden');
      if (!$('.masterstudy-course-player-curriculum').hasClass('masterstudy-course-player-curriculum_open')) {
        $('.masterstudy-course-player-content').toggleClass('masterstudy-course-player-content_open-sidebar');
      }
      if (window.matchMedia('(max-width: 1366px)').matches) {
        $('.masterstudy-course-player-curriculum').removeClass('masterstudy-course-player-curriculum_open');
        $('[data-id="masterstudy-curriculum-switcher"]').removeClass('masterstudy-switch-button_active');
      }
    });

    // open-close curriculum sidebar
    $('[data-id="masterstudy-curriculum-switcher"]').click(function () {
      $(this).toggleClass('masterstudy-switch-button_active');
      var currentUrl = window.location.href;
      var url = new URL(currentUrl);
      if (url.searchParams.has('curriculum_open')) {
        url.searchParams["delete"]('curriculum_open');
      } else if (localStorage.getItem('curriculum_open') !== 'yes') {
        url.searchParams.set('curriculum_open', 'yes');
      }
      history.pushState({}, '', url.toString());
      if (localStorage.getItem('curriculum_open') === 'yes') {
        localStorage.removeItem('curriculum_open');
      } else {
        localStorage.setItem('curriculum_open', 'yes');
      }
      $('.masterstudy-course-player-curriculum').toggleClass('masterstudy-course-player-curriculum_open');
      $('body').toggleClass('masterstudy-course-player-body-hidden');
      if (!$('.masterstudy-course-player-discussions').hasClass('masterstudy-course-player-discussions_open')) {
        $('.masterstudy-course-player-content').toggleClass('masterstudy-course-player-content_open-sidebar');
      }
      if (window.matchMedia('(max-width: 1366px)').matches) {
        $('.masterstudy-course-player-discussions').removeClass('masterstudy-course-player-discussions_open');
        $('.masterstudy-course-player-header__discussions').removeClass('masterstudy-course-player-header__discussions_open');
      }
    });

    // tabs toggler
    $('.masterstudy-course-player-header .masterstudy-tabs__item').on('click', function () {
      if ($(this).data('id') === 'materials') {
        document.querySelector('.masterstudy-course-player-lesson-materials').scrollIntoView({
          behavior: 'smooth'
        });
      } else if ($(this).data('id') === 'lesson') {
        document.querySelector('.masterstudy-course-player-content__header').scrollIntoView({
          behavior: 'smooth',
          block: 'start',
          inline: 'nearest',
          offsetTop: -20
        });
      }
    });

    // tabs toggler depending on the visibility in the window
    var parent;
    if ($(window).width() < 1025) {
      parent = window;
    } else {
      parent = '.masterstudy-course-player-content__wrapper';
    }
    $(parent).on('scroll', function () {
      var windowTop = $(window).scrollTop();
      var windowBottom = windowTop + $(window).height();
      var materialsElement = $('.masterstudy-course-player-lesson-materials');
      if (materialsElement.length > 0) {
        var materialsElementTop = materialsElement.offset().top;
        var materialsElementBottom = materialsElementTop + materialsElement.height();
        if (materialsElementTop >= windowTop && materialsElementBottom <= windowBottom) {
          $('.masterstudy-course-player-header__navigation [data-id="materials"]').addClass('masterstudy-tabs__item_active');
          $('.masterstudy-course-player-header__navigation [data-id="lesson"]').removeClass('masterstudy-tabs__item_active');
        } else {
          $('.masterstudy-course-player-header__navigation [data-id="materials"]').removeClass('masterstudy-tabs__item_active');
          $('.masterstudy-course-player-header__navigation [data-id="lesson"]').addClass('masterstudy-tabs__item_active');
        }
      }
    });

    // dark mode toggler
    $('.masterstudy-course-player-header__dark-mode').find('.masterstudy-dark-mode-button').click(function () {
      var dark_mode = false;
      if ($(this).hasClass('masterstudy-dark-mode-button_style-dark')) {
        dark_mode = true;
      }
      $('.masterstudy-course-player-header').toggleClass('masterstudy-course-player-header_dark-mode');
      $('.masterstudy-course-player-content').toggleClass('masterstudy-course-player-content_dark-mode');
      $('.masterstudy-course-player-navigation').toggleClass('masterstudy-course-player-navigation_dark-mode');
      $('.masterstudy-course-player-header__curriculum').find('.masterstudy-switch-button').toggleClass('masterstudy-switch-button_dark-mode');
      $('.masterstudy-course-player-header').find('.masterstudy-tabs').toggleClass('masterstudy-tabs_dark-mode');
      $('.masterstudy-course-player-curriculum').find('.masterstudy-curriculum-accordion').toggleClass('masterstudy-curriculum-accordion_dark-mode');
      $('.masterstudy-course-player-navigation').find('.masterstudy-nav-button').toggleClass('masterstudy-nav-button_dark-mode');
      $('.masterstudy-course-player-discussions').find('.masterstudy-discussions').toggleClass('masterstudy-discussions_dark-mode');
      $('.masterstudy-course-player-quiz').find('.masterstudy-pagination').toggleClass('masterstudy-pagination_dark-mode');
      $('.masterstudy-course-player-quiz__navigation-tabs').find('.masterstudy-tabs-pagination').toggleClass('masterstudy-tabs-pagination_dark-mode');
      $('.masterstudy-alert').toggleClass('masterstudy-alert_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-countdown').toggleClass('masterstudy-countdown_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-file-upload').toggleClass('masterstudy-file-upload_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-file-attachment').toggleClass('masterstudy-file-attachment_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-audio-player').toggleClass('masterstudy-audio-player_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-audio__recorder').toggleClass('masterstudy-audio__recorder_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-hint').toggleClass('masterstudy-hint_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-wp-editor').toggleClass('masterstudy-wp-editor_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-attachment-media').toggleClass('masterstudy-wp-editor_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-attachment-media').toggleClass('masterstudy-attachment-media_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-loader').toggleClass('masterstudy-loader_dark-mode');
      $('.masterstudy-course-player-content').find('.masterstudy-progress').toggleClass('masterstudy-progress_dark-mode');
      ChangeEditorDarkMode(!dark_mode);
    });
    function ChangeEditorDarkMode(dark_mode) {
      if ($('.masterstudy-course-player-assignments__edit').length > 0) {
        var editor_id = $('.masterstudy-course-player-assignments__edit').data('editor');
        var editor = tinyMCE.get(editor_id);
        var body_dark_styles = settings.theme_fonts ? "\n                    body {\n                        line-height: normal;\n                        background-color: rgba(23,23,23,1);\n                        color: rgba(255,255,255,0.7); }\n                    " : "\n                    body {\n                        font-family: 'Albert Sans', sans-serif;\n                        line-height: normal;\n                        background-color: rgba(23,23,23,1);\n                        color: rgba(255,255,255,0.7); }\n                    ";
        var body_light_styles = settings.theme_fonts ? "\n                    body {\n                        line-height: normal;\n                        background-color: rgba(255,255,255,1);\n                        color: rgba(0,25,49,1);\n                    " : "\n                    body {\n                        font-family: 'Albert Sans', sans-serif;\n                        line-height: normal;\n                        background-color: rgba(255,255,255,1);\n                        color: rgba(0,25,49,1);\n                    ";
        if (editor.iframeElement === undefined) {
          setTimeout(function () {
            ChangeEditorDarkMode();
          }, 500);
        } else {
          var customStyles = dark_mode ? body_dark_styles : body_light_styles;
          var iframeDocument = editor.iframeElement.contentDocument || editor.iframeElement.contentWindow.document;
          var _styleElement = iframeDocument.createElement('style');
          _styleElement.innerHTML = customStyles;
          iframeDocument.head.appendChild(_styleElement);
        }
        var styleElement = document.createElement('style');
        var styles = dark_mode ? "\n                body .mce-container.mce-panel.mce-floatpanel {\n                    background-color: rgba(30,30,30,1);\n                    border: 1px solid rgba(255,255,255,.05);\n                    border-radius: 4px;\n                    color: rgba(255,255,255,1);\n                    margin-top: 3px;\n                }\n                body .mce-container.mce-panel.mce-floatpanel .mce-menu-item:hover {\n                    background-color: rgba(255,255,255,.05);\n                }\n                body .mce-container.mce-panel.mce-floatpanel .mce-menu-item.mce-active {\n                    background-color: rgba(255,255,255,.05);\n                }\n                body .masterstudy-wp-editor__word-count {\n                    color: rgba(255,255,255,1);\n                }\n                " : "\n                body .mce-container.mce-panel.mce-floatpanel {\n                    background-color: rgba(255,255,255,1);\n                    border: 1px solid rgba(238,241,247,1);\n                    border-radius: 4px;\n                    color: rgba(0,25,49,1);\n                    margin-top: 3px;\n                }\n                body .mce-container.mce-panel.mce-floatpanel .mce-menu-item:hover {\n                    background-color: rgba(34,122,255,1);\n                    color: rgba(255,255,255,1);\n                }\n                body .mce-container.mce-panel.mce-floatpanel .mce-menu-item.mce-active {\n                    background-color: rgba(34,122,255,1);\n                    color: rgba(255,255,255,1);\n                }\n                body .masterstudy-wp-editor__word-count {\n                    color: rgba(0,25,49,1);\n                }\n                ";
        styleElement.textContent = styles;
        document.head.appendChild(styleElement);
      }
    }
    function isElementInView(selector) {
      var element = document.querySelector(selector);
      if (element !== null) {
        var rect = element.getBoundingClientRect();
        var windowHeight = window.innerHeight || document.documentElement.clientHeight;
        var windowWidth = window.innerWidth || document.documentElement.clientWidth;
        return rect.top < windowHeight && rect.bottom >= 0 && rect.left < windowWidth && rect.right >= 0;
      }
      return false;
    }
  });
})(jQuery);