(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

(function ($) {
  /**
   * Manage preset settings.
   *
   * @since 1.5.0
   */
  var Presets = {
    init: function init() {
      this.bindEvents();
    },
    bindEvents: function bindEvents() {
      $(document).on('click', '#raven-preset-library-sync-button', function () {
        var self = this;
        $(this).removeClass('success').removeClass('error').addClass('loading');
        wp.ajax.post('raven_sync_libraries', {
          library: 'presets'
        }).done(function () {
          $(self).removeClass('loading').addClass('success');
        }).fail(function () {
          $(self).removeClass('loading').addClass('error');
        });
        return false;
      });
    }
  };
  Presets.init();
})(jQuery);

},{}]},{},[1]);
