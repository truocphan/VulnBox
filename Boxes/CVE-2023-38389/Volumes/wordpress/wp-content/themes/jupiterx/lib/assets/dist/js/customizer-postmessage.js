'use strict';

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

(function ($, wp) {
  var _$extend;

  /**
   * Borrowed object from Kirki.
   *
   * The object is use to render style output in the preview scene.
   *
   * @since 1.0.0
   */
  var postMessage = kirkiPostMessage || {};

  /**
   * Kirki control style filter.
   *
   * @since 1.0.0
   */
  postMessage.fields = _.extend(postMessage.fields, (_$extend = {
    /**
     * Box model control.
     *
     * @since 1.0.0
     */
    'jupiterx-box-model': function jupiterxBoxModel(value, output, setting) {
      var exclude = setting.exclude || [],
          boxParts = ['margin', 'padding'],
          styles = '',
          setPartStyles = void 0;

      setPartStyles = function setPartStyles(part) {
        var positions = _.map(['top', 'right', 'bottom', 'left'], function (position) {
          return part + '_' + position;
        });

        // Create positions style properties.
        _.each(positions, function (position) {
          if (value[position] && value[position] !== '') {
            var unit = value[part + '_unit'] || 'rem';
            unit = isFinite(value[position]) ? unit : '';
            styles += position.replace('_', '-') + ': ' + value[position] + unit + ';';
          }
        });
      };

      _.each(boxParts, function (part) {
        if (!_.contains(exclude, part)) {
          setPartStyles(part);
        }
      });

      return styles;
    },

    /**
     * Border control.
     *
     * @since 1.0.0
     */
    'jupiterx-border': function jupiterxBorder(value, output, setting) {
      var styles = '',
          units = output.units || 'px',
          property = output.property || 'border',
          border = _.defaults(value, {
        size: {},
        radius: {},
        width: {},
        style: 'solid',
        color: ''
      });

      if (output.choice && output.property) {
        switch (output.choice) {
          case 'size':
          case 'radius':
          case 'width':
            if (!_.isEmpty(border[output.choice])) {
              styles += postMessage.fields['jupiterx-input'](border[output.choice], { property: output.property });
            }
            break;

          case 'style':
          case 'color':
            if (border[output.choice] !== '') {
              styles += output.property + ': ' + postMessage.util.processValue(output, border[output.choice]) + ';';
            }
            break;
        }

        return styles;
      }

      if (!_.isEmpty(border.size)) {
        styles += postMessage.fields['jupiterx-input'](border.size, { property: 'width' });
      }

      if (!_.isEmpty(border.radius)) {
        styles += postMessage.fields['jupiterx-input'](border.radius, { property: 'border-radius' });
      }

      if (!_.isEmpty(border.width)) {
        styles += postMessage.fields['jupiterx-input'](border.width, { property: property + '-width' });
      }

      if (border.style !== '') {
        styles += property + '-style: ' + border.style + ';';
      }

      if (border.color !== '') {
        styles += property + '-color: ' + border.color + ';';
      }

      return styles;
    },

    /**
     * Background control.
     *
     * @since 1.0.0
     */
    'jupiterx-background': function jupiterxBackground(value, output, setting) {
      var styles = '',
          background = _.defaults(value, {
        type: 'classic',
        color: '',
        image: '',
        repeat: 'no-repeat',
        attachment: 'scroll',
        size: 'auto',
        position: 'initial',
        gradient_type: 'linear',
        angle: '90',
        color_from: 'transparent',
        color_to: 'transparent'
      });

      if (background.type === 'classic') {
        if (background.color !== '') {
          styles += 'background-color: ' + background.color + ';';
        }

        if (background.image !== '') {
          styles += 'background-image: ' + postMessage.util.backgroundImageValue(background.image) + ';';

          _.each(['position', 'repeat', 'attachment', 'size'], function (property) {
            styles += 'background-' + property + ': ' + background[property] + ';';
          });
        } else {
          styles += 'background-image: none;';
        }
      }

      if (background.type === 'gradient') {
        if (background.gradient_type === 'radial') {
          styles += 'background: radial-gradient(' + background.color_from + ', ' + background.color_to + ');';
        }

        if (background.gradient_type === 'linear') {
          styles += 'background: linear-gradient(' + background.angle + 'deg, ' + background.color_from + ', ' + background.color_to + ');';
        }
      }

      return styles;
    }

  }, _defineProperty(_$extend, 'jupiterx-background', function jupiterxBackground(value, output, setting) {
    var styles = '',
        background = _.defaults(value, {
      type: 'classic',
      color: '',
      image: '',
      repeat: 'no-repeat',
      attachment: 'scroll',
      size: 'auto',
      position: 'initial',
      gradient_type: 'linear',
      angle: '90',
      color_from: 'transparent',
      color_to: 'transparent'
    });

    if (background.type === 'classic') {
      if (background.color !== '') {
        styles += 'background-color: ' + background.color + ';';
      }

      if (background.image !== '') {
        styles += 'background-image: ' + postMessage.util.backgroundImageValue(background.image) + ';';

        _.each(['position', 'repeat', 'attachment', 'size'], function (property) {
          styles += 'background-' + property + ': ' + background[property] + ';';
        });
      } else {
        styles += 'background-image: none;';
      }
    }

    if (background.type === 'gradient') {
      if (background.gradient_type === 'radial') {
        styles += 'background: radial-gradient(' + background.color_from + ', ' + background.color_to + ');';
      }

      if (background.gradient_type === 'linear') {
        styles += 'background: linear-gradient(' + background.angle + 'deg, ' + background.color_from + ', ' + background.color_to + ');';
      }
    }

    return styles;
  }), _defineProperty(_$extend, 'jupiterx-input', function jupiterxInput(value, output, setting) {
    if (!_.isObject(value) || !value.size || value.size === '' || !value.unit) {
      return '';
    }

    var unit = '-' !== value.unit ? value.unit : '',
        cssValue = '' + value.size + unit;

    return output.property + ': ' + postMessage.util.processValue(output, cssValue) + ';';
  }), _defineProperty(_$extend, 'jupiterx-typography', function jupiterxTypography(value, output, setting) {
    var styles = '',
        withUnit = ['font_size', 'line_height', 'letter_spacing'];

    // If empty font family set it to inherit.
    if (!_.isUndefined(value.font_family) && _.isEmpty(value.font_family)) {
      value.font_family = 'inherit';
    }

    _.each(value, function (rawValue, property) {
      var css = {
        property: property.replace('_', '-'),
        value: rawValue
      };

      if (output.choice && output.choice !== property) {
        return;
      }

      if (_.contains(withUnit, property)) {
        var withUnitOutput = _.extend({
          property: css.property
        }, output);

        styles += postMessage.fields['jupiterx-input'](rawValue, withUnitOutput, setting);
        return;
      }

      styles += css.property + ': ' + css.value + ';';
    });

    return styles;
  }), _defineProperty(_$extend, 'jupiterx-box-shadow', function jupiterxBoxShadow(value, output, setting) {
    var styles = '',
        units = output.units || 'px',
        boxShadow = _.defaults(value, {
      horizontal: 0,
      vertical: 0,
      blur: 0,
      spread: 0,
      color: '#000000',
      position: '',
      unit: 'px'
    }),
        numberedProps = _.pick(boxShadow, ['horizontal', 'vertical', 'blur', 'spread']);

    // Check if at least one is not zero.
    numberedProps = _.pick(numberedProps, function (value) {
      return value !== 0 && value !== '';
    });

    if (!_.isEmpty(numberedProps)) {
      styles += 'box-shadow: ';
      styles += '' + (boxShadow.horizontal || 0) + units + ' ';
      styles += '' + (boxShadow.vertical || 0) + units + ' ';
      styles += '' + (boxShadow.blur || 0) + units + ' ';
      styles += '' + (boxShadow.spread || 0) + units + ' ';
      styles += boxShadow.color + ' ';
      styles += boxShadow.position + ';';
    }

    return styles;
  }), _$extend));

  /**
   * Get media query output.
   *
   * @since 1.0.0
   *
   * @returns {object}
   */
  var getMediaQueryOutput = function getMediaQueryOutput(output, mediaQuery, device) {
    if (device === 'desktop') {
      return _.extend({}, output);
    }

    return _.extend({
      'media_query': mediaQuery
    }, output);
  };

  // Bind value for each settings.
  _.each(jupiterPostMessage.settings, function (field) {
    wp.customize(field.settings, function (setting) {
      setting.bind(function (newValue) {
        var styles = '';

        if (field.responsive) {
          _.each(field.output, function (output) {
            _.each(jupiterPostMessage.responsiveDevices, function (mediaQuery, device) {
              var mediaQueryOutput = getMediaQueryOutput(output, mediaQuery, device);

              if (output.device && output.device !== device) {
                return;
              }

              if (newValue[device]) {
                styles += postMessage.css.fromOutput(mediaQueryOutput, newValue[device], field.type, field);
              }
            });
          });
        } else {
          _.each(field.output, function (output) {
            styles += postMessage.css.fromOutput(output, newValue, field.type, field);
          });
        }

        postMessage.styleTag.addData(field.settings, styles);
      });
    });
  });
})(jQuery, wp);