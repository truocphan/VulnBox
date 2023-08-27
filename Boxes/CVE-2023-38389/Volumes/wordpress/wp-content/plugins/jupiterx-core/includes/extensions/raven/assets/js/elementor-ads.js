(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

/* eslint no-undef: 0 */
(function ($, window) {
  function hideProKits() {
    if (true === elementorAppConfig.is_pro) {
      return;
    }

    var queryString = window.location.search;
    var urlParams = new URLSearchParams(queryString);
    var page = urlParams.get('page');

    if ('elementor-app' === page) {
      var interval = setInterval(function () {
        if ($('.e-kit-library__kit-item-subscription-plan-badge').length > 0) {
          $('.e-kit-library__kit-item-subscription-plan-badge').parent().parent().parent().hide();
          clearInterval(interval);
        }
      }, 500);
    }
  }

  function elementorLibraryModification() {
    $(document).on('click', '.elementor-add-template-button', function () {
      var interval = setInterval(function () {
        if ($(window.parent.document).find('#elementor-template-library-header-menu').length > 0) {
          $(window.parent.document).find('#elementor-template-library-header-menu').find('div[data-tab="library/templatesJX"]').trigger('click');
          clearInterval(interval);
        }
      }, 100);
    });
  }

  function init() {
    if ($('body').hasClass('sticky-menu')) {
      hideProKits();
    }

    if ($('body').hasClass('theme-jupiterx')) {
      elementorLibraryModification();
    }
  }

  $(window).on('load', init);
})(jQuery, window);

},{}]},{},[1]);
