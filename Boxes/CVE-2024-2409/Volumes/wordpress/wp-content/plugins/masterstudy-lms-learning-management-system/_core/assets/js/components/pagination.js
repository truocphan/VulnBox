"use strict";

(function ($) {
  $(document).ready(function () {
    var pagesContainer = $(".masterstudy-pagination");
    var pagesWrapper = $(".masterstudy-pagination").find(".masterstudy-pagination__wrapper");
    var pagesList = pagesContainer.find(".masterstudy-pagination__list");
    var scrollButtonNext = pagesContainer.find(".masterstudy-pagination__button-next");
    var scrollButtonPrev = pagesContainer.find(".masterstudy-pagination__button-prev");
    var numericFields = ["max_visible_pages", "total_pages", "current_page", "item_width"];
    numericFields.forEach(function (field) {
      pages_data[field] = parseInt(pages_data[field]);
    });
    var containerWidth = pages_data.item_width * pages_data.max_visible_pages,
      currentPosition = 0,
      currentPage = pages_data.current_page,
      totalPages = pages_data.total_pages,
      centeredPage = Math.round(pages_data.max_visible_pages / 2),
      maxPosition = pages_data.item_width * (totalPages - pages_data.max_visible_pages),
      noScroll = totalPages <= pages_data.max_visible_pages;
    if (pages_data.max_visible_pages < totalPages) {
      pagesWrapper.css("width", containerWidth);
    } else {
      pagesWrapper.css("width", totalPages * pages_data.item_width);
    }

    // Page onload
    prevNextButtonState($('.masterstudy-pagination'), currentPage, totalPages);
    currentPosition = calculateInitialPosition(currentPage, centeredPage, totalPages, maxPosition);
    pagesList.find("[data-id=\"".concat(currentPage, "\"]")).parent().addClass("masterstudy-pagination__item_current");
    pagesList.animate({
      left: -currentPosition + "px"
    }, 50);
    scrollButtonNext.click(function (e) {
      e.preventDefault();
      if (pages_data.is_queryable && currentPage < totalPages) {
        updatePageQueryParam(currentPage + 1);
      }
      if (currentPage < totalPages) {
        currentPage = currentPage + 1;
      }
      if (currentPage === totalPages) {
        $(this).addClass("masterstudy-pagination__button_disabled");
      } else {
        $(this).removeClass("masterstudy-pagination__button_disabled");
      }
      if (currentPage !== 1) {
        $(this).parent().find(".masterstudy-pagination__button-prev").removeClass("masterstudy-pagination__button_disabled");
      }
      if (currentPage > centeredPage && currentPosition < maxPosition) {
        currentPosition += pages_data.item_width;
        pagesList.animate({
          left: -currentPosition + "px"
        }, 50);
      }
      setCurrentPage(pagesList, currentPage, 'masterstudy-pagination__item_current');
    });
    scrollButtonPrev.click(function (e) {
      e.preventDefault();
      if (pages_data.is_queryable && currentPage > 1) {
        updatePageQueryParam(currentPage - 1);
      }
      if (currentPage > 1) {
        currentPage = currentPage - 1;
      }
      if (currentPage === 1) {
        $(this).addClass("masterstudy-pagination__button_disabled");
      } else {
        $(this).removeClass("masterstudy-pagination__button_disabled");
      }
      if (currentPage !== totalPages) {
        $(this).parent().find(".masterstudy-pagination__button-next").removeClass("masterstudy-pagination__button_disabled");
      }
      if (!currentPage < centeredPage && currentPage < totalPages - centeredPage + 1 && currentPosition > 0) {
        currentPosition -= pages_data.item_width;
        pagesList.animate({
          left: -currentPosition + "px"
        }, 50);
      }
      setCurrentPage(pagesList, currentPage, 'masterstudy-pagination__item_current');
    });
    $(".masterstudy-pagination__item-block").click(function () {
      currentPage = $(this).data("id");
      var container = $(this).closest(".masterstudy-pagination");
      if (currentPage < centeredPage) {
        currentPosition = 0;
      } else if (currentPage > totalPages - centeredPage + 1) {
        currentPosition = noScroll ? 0 : maxPosition;
      } else {
        currentPosition = (currentPage - centeredPage) * pages_data.item_width;
      }
      prevNextButtonState(container, currentPage, totalPages);
      $(this).parent().siblings().removeClass("masterstudy-pagination__item_current");
      $(this).parent().addClass("masterstudy-pagination__item_current");
      pagesList.animate({
        left: -currentPosition + "px"
      }, 50);
      if (pages_data.is_queryable) {
        updatePageQueryParam(currentPage);
      }
    });
    function calculateInitialPosition(currentPage, centeredPage, totalPages, maxPosition) {
      var position;
      if (currentPage <= centeredPage) {
        position = 0;
      } else if (currentPage > totalPages - centeredPage) {
        position = maxPosition;
      } else {
        position = (currentPage - centeredPage) * pages_data.item_width;
      }
      return position;
    }
    function setCurrentPage(pagesList, currentPage, className) {
      pagesList.find("[data-id=\"".concat(currentPage, "\"]")).parent().siblings().removeClass(className);
      pagesList.find("[data-id=\"".concat(currentPage, "\"]")).parent().addClass(className);
    }
    function prevNextButtonState(container, currentPage, totalPages) {
      var $btnClassPrev = '.masterstudy-pagination__button-prev';
      var $btnClassNext = '.masterstudy-pagination__button-next';
      container.find($btnClassPrev).removeClass("masterstudy-pagination__button_disabled");
      container.find($btnClassNext).removeClass("masterstudy-pagination__button_disabled");
      if (totalPages === 1) {
        container.find($btnClassPrev).addClass("masterstudy-pagination__button_disabled");
        container.find($btnClassNext).addClass("masterstudy-pagination__button_disabled");
      } else if (currentPage === 1) {
        container.find($btnClassPrev).addClass("masterstudy-pagination__button_disabled");
      } else if (currentPage === totalPages) {
        container.find($btnClassNext).addClass("masterstudy-pagination__button_disabled");
      }
    }
    function updatePageQueryParam(pageNumber) {
      var currentUrl = window.location.href;
      var urlParams = new URLSearchParams(window.location.search);
      var queryName = "page";
      if (urlParams.has(queryName)) {
        urlParams.set(queryName, pageNumber);
      } else {
        urlParams.append(queryName, pageNumber);
      }
      // Set query params and reload
      var queryUrl = currentUrl.split("?")[0] + "?" + urlParams.toString();
      window.history.replaceState({}, document.title, queryUrl);
      window.location.href = queryUrl;
    }
  });
})(jQuery);