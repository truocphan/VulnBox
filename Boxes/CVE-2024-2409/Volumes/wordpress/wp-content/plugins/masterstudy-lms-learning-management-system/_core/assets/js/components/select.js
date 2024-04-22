"use strict";

(function ($) {
  $(document).ready(function () {
    $.each($('.masterstudy-select'), function (i, select) {
      var clearSelect = $(select).find('.masterstudy-select__clear');
      var selectOptions = $(select).find('.masterstudy-select__option');
      var placeholder = $(select).find('.masterstudy-select__placeholder');
      var selectInput = $(select).find('.masterstudy-select__input');
      var isQueryable = $(select).data('queryable');
      var initialValue = placeholder.data('initial');
      var selectName = selectInput.attr('name');
      var urlParams = new URLSearchParams(window.location.search);
      var currentUrl = window.location.href;

      // Open select dropdown.
      $(select).on('click', function (e) {
        e.stopPropagation();
        if (!$(e.target).is('[class^="masterstudy-select__clear-"]')) {
          $(select).toggleClass('masterstudy-select_open');
        }
      });

      // Close select if clicked outside it.
      $(document).on('click', function (e) {
        if ($(select).parent().has(e.target).length === 0) {
          $(select).removeClass('masterstudy-select_open');
        }
      });

      // Clear select element value.
      clearSelect.on('click', function () {
        $(select).removeClass('masterstudy-select_selected');
        placeholder.html(placeholder.data('placeholder'));
        selectInput.val(initialValue);
        selectOptions.removeClass('masterstudy-select__option_selected');
        if (isQueryable) {
          urlParams = new URLSearchParams(window.location.search);
          if (initialValue) {
            urlParams.set(selectName, initialValue);
            urlParams["delete"]('paged');
          } else {
            urlParams["delete"](selectName);
          }
          var queryUrl = currentUrl.split('?')[0] + '?' + urlParams.toString();
          window.history.replaceState({}, document.title, queryUrl);
          window.location.href = queryUrl;
        }
      });

      // Select option.
      $.each(selectOptions, function (i, option) {
        // Change select valeu on option click
        $(option).on('click', function () {
          var selectVal = $(this).data('value');
          // Change classes
          $(select).addClass('masterstudy-select_selected');
          $(this).toggleClass('masterstudy-select__option_selected');
          $(option).siblings().removeClass('masterstudy-select__option_selected');
          // Set values
          placeholder.html($(this).html());
          selectInput.val(selectVal);
          if (isQueryable) {
            // Get curren url params
            urlParams = new URLSearchParams(window.location.search);
            urlParams["delete"]('paged');
            if (urlParams.has(selectName)) {
              urlParams.set(selectName, selectVal);
            } else {
              urlParams.append(selectName, selectVal);
            }
            // Set query params and reload
            var queryUrl = currentUrl.split('?')[0] + '?' + urlParams.toString();
            window.history.replaceState({}, document.title, queryUrl);
            window.location.href = queryUrl;
          }
        });
        // If value set on url params
        if ($(option).data('value') == urlParams.get(selectName)) {
          placeholder.html($(option).html());
          $(select).addClass('masterstudy-select_selected');
          $(option).toggleClass('masterstudy-select__option_selected');
          selectInput.val($(option).data('value'));
        }
      });
    });
  });
})(jQuery);