"use strict";

(function ($) {
  $(document).ready(function () {
    var tabContainer = $('.masterstudy-tabs-pagination');
    var tabWrapper = $('.masterstudy-tabs-pagination').find('.masterstudy-tabs-pagination__wrapper');
    var tabList = tabContainer.find('.masterstudy-tabs-pagination__list');
    var scrollButtonNext = tabContainer.find('.masterstudy-tabs-pagination__button-next');
    var scrollButtonPrev = tabContainer.find('.masterstudy-tabs-pagination__button-prev');
    var numericFields = ['item_width', 'max_visible_tabs', 'tabs_quantity'];
    numericFields.forEach(function (field) {
      tabs_data[field] = parseInt(tabs_data[field]);
    });
    if (window.matchMedia('(max-width: 1023.98px)').matches) {
      tabs_data.max_visible_tabs = Math.round(tabs_data.max_visible_tabs / 2);
    }
    var containerWidth = tabs_data.item_width * tabs_data.max_visible_tabs,
      maxPosition = tabs_data.item_width * (tabs_data.tabs_quantity - tabs_data.max_visible_tabs),
      currentPosition = 0;
    if (tabs_data.max_visible_tabs < tabs_data.tabs_quantity) {
      if (tabs_data.vertical) {
        tabWrapper.css('height', containerWidth);
      } else {
        tabWrapper.css('width', containerWidth);
      }
    } else {
      if (tabs_data.vertical) {
        tabWrapper.css('height', tabs_data.tabs_quantity * tabs_data.item_width);
      } else {
        tabWrapper.css('width', tabs_data.tabs_quantity * tabs_data.item_width);
      }
    }
    updateButtonVisibility();
    scrollButtonNext.on('click', function (e) {
      e.preventDefault();
      if (currentPosition < maxPosition) {
        currentPosition += tabs_data.item_width;
        if (tabs_data.vertical) {
          tabList.animate({
            'top': -currentPosition + 'px'
          }, 50);
        } else {
          tabList.animate({
            'left': -currentPosition + 'px'
          }, 50);
        }
        updateButtonVisibility();
      }
    });
    scrollButtonPrev.on('click', function (e) {
      e.preventDefault();
      if (currentPosition > 0) {
        currentPosition -= tabs_data.item_width;
        if (tabs_data.vertical) {
          tabList.animate({
            'top': -currentPosition + 'px'
          }, 50);
        } else {
          tabList.animate({
            'left': -currentPosition + 'px'
          }, 50);
        }
        updateButtonVisibility();
      }
    });
    function updateButtonVisibility() {
      if (currentPosition === 0) {
        scrollButtonPrev.css('display', 'none');
      } else {
        scrollButtonPrev.css('display', 'flex');
      }
      if (currentPosition === maxPosition) {
        scrollButtonNext.css('display', 'none');
      } else {
        scrollButtonNext.css('display', 'flex');
      }
    }
    ;
  });
})(jQuery);