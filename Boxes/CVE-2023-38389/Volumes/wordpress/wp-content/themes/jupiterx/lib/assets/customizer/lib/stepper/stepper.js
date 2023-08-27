// jquery.stepper.js
// Original: https://github.com/ncou/jquery.stepper.basic
// Artbees: https://github.com/ArtbeesThemes/jquery.stepper.basic
// ------------------------------------------------------
// Author: NCOU
//
// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
(function($, window, document, undefined) {
  "use strict";
  var pluginName = "stepper",
    defaults = {
      selectorProgressBar: ".stepper-progress",
      selectorInputNumber: ".stepper-number",
      classNameChanging: "is-changing",
      value: "",
      decimals: 0,
      min: 0,
      max: 100,
      step: 1,
      unit: ""
    };

  // The actual plugin constructor
  function Plugin(element, options) {
    this.element = element;

    this.settings = $.extend({}, defaults, options);

    this._defaults = defaults;
    this._name = pluginName;

    this.init();
  }

  // Avoid Plugin.prototype conflicts
  $.extend(Plugin.prototype, {
    init: function() {
      // local variable
      this._curDown = false;
      this._mouseDownX = 0;
      this._mouseDownValue = 0;

      // Cache elements
      this.$el = $(this.element);
      this.$input = this.$el;
      this.$progress = this.$el.find(this.settings.selectorProgressBar);

      // init values
      this.decimals = this.$input.attr("decimals") || this.settings.decimals;
      this.min = this.$input.attr("min") || this.settings.min;
      this.max = this.$input.attr("max") || this.settings.max;
      this.step = this.$input.attr("step") || this.settings.step;
      this.unit = this.$input.attr("unit") || this.settings.unit;

      this.value = this.$input.val() || this.settings.value;

      this.setValue(this.value);

      // Bind events
      this.$input.on("keydown", this.onKeyPress.bind(this));
      this.$input.on("blur", this.onBlur.bind(this));
      this.$input.on("paste input", this.onChange.bind(this));
      this.$el.on("mousedown touchstart", this.onMouseDown.bind(this));
      this.$el.on("wheel", this.onMouseWheel.bind(this));
      this.$el.on("keydown", this.onKeydown.bind(this));
      this.$el.on("mouseup touchend", this.onMouseUp.bind(this));
      // $(document).on("mousemove touchmove", this.onMouseMove.bind(this));
      this.$input.on('stepper.destroy', this.destroy.bind(this));
    },

    destroy: function(e, unit) {
      this.$input.off('keydown');
      this.$input.off('blur');
      this.$input.off('paste input');
      this.$el.off('mousedown touchstart');
      this.$el.off('wheel');
      this.$el.off('keydown');
      this.$el.off('mouseup touchend');

      if ($.isNumeric(this.getValue()) && 'px' == unit) {
        this.setValue(Math.round(this.getValue()));
      }
    },

    onMouseDown: function(e) {
      this._mouseDownX = e.clientX || e.originalEvent.touches[0].clientX;
      this._changeStart();
    },

    onMouseUp: function(e) {
      if ( ! $.isNumeric(this.getValue()) ) { return false; }
      this._changeEnd();
    },

    onMouseMove: function(e) {
      if ( ! $.isNumeric(this.getValue()) ) { return false; }
      if (this._curDown === true) {
        var t =
          (e.clientX || e.originalEvent.touches[0].clientX) - this._mouseDownX;
        this.setValue(this._mouseDownValue + t * this.step);
      }
    },

    onMouseWheel: function(e) {
      var value = this.getValue();

      if (!$.isNumeric(value) && value.length) {
        return false;
      }

      // prevent [wheel increase and mousemove increase] and [wheel increase] if the input field is not focused
      if (this.$input.is(":focus")) {
        e.preventDefault();
        // delta is 1 if scroll up, or -1 if scroll down
        var d = e.originalEvent.deltaY < 0 ? 1 : -1;
        this.setValue(this.getValue() + d * this.step);
      }
    },

    onKeydown: function(e) {
      if (e.which == 38 || e.which == 40) {
        var value = this.getValue();

        if (!$.isNumeric(value) && value.length) {
          return false;
        }

        if (!this.$input.is(":focus")) {
          return false;
        }

        if (e.which == 38) {
          this.setValue(this.getValue() + 1 * this.step);
        }

        if (e.which == 40) {
          this.setValue(this.getValue() - 1 * this.step);
        }
      }
    },

    onChange: function(e) {
      this._updateProgress(this.getValue());
    },

    onBlur: function(e) {
      this._changeEnd();
      this.setValue(this.getValue());
    },

    onKeyPress: function(e) {
      // exit the input field if key pressed is 'Enter'
      if (e.keyCode === 13) {
        this.$input.blur();
      }
    },

    getValue: function() {
      var value = this.$input.val();

      if (!$.isNumeric(value)) {
        return value;
      }

      return parseFloat(value) || 0;
    },

    setValue: function(amount) {
      var value;

      if ($.isNumeric(amount)) {
        value = Math.max(Math.min(amount, this.max), this.min);
        value = this._roundValue(value);

        var n = value;

        n += this.unit;
        this.$input.val(n);

        this._updateProgress(value);
        return;
      }

      if ('auto' != amount) {
        amount = ''
      }

      this.$input.val(amount);
      this._updateProgress(value);
    },

    _updateProgress: function(v) {
      var r = this._valueToPercent(v) / 100;
      this.$progress.css("transform", "scaleX(" + r + ")");

      this.$input.trigger("change");
    },

    _percentToValue: function(v) {
      return this.min + v / 100 * (this.max - this.min);
    },

    _valueToPercent: function(v) {
      var t = (v - this.min) / (this.max - this.min) * 100;
      return Math.max(Math.min(t, 100), 0);
    },

    _roundValue: function(v) {
      var maxDecimals = 2;

      var t = Math.pow(10, maxDecimals);
      return Math.round(v * t) / t;
    },

    _changeStart: function() {
      this._curDown = true;
      this.$el.addClass(this.settings.classNameChanging);
    },

    _changeEnd: function() {
      this._curDown = false;
      this.$el.removeClass(this.settings.classNameChanging);
    }
  });

  // A lightweight plugin wrapper around the constructor, preventing against multiple instantiations
  $.fn[pluginName] = function(options) {
    return this.each(function() {
      $.data(this, "plugin-" + pluginName, new Plugin(this, options));
    });
  };
})(jQuery, window, document);
