'use strict';

(function ($) {

  /**
   * Do not run any codes below if ACF does not exist.
   */
  if (typeof acf === 'undefined') {
    return;
  }

  /**
   * Template field.
   *
   * @since 1.2.0
   */
  acf.add_action('ready_field/type=jupiterx_template', jupiterx_template_field);
  acf.add_action('append_field/type=jupiterx_template', jupiterx_template_field);

  function jupiterx_template_field($field) {
    var $select = $field.find('select');
    var $editButton = $field.find('.edit-button');
    var $newButton = $field.find('.new-button');
    var settings = $select.data('settings');

    function updateHasValue() {
      var hasValue = $select.val() !== '' && $select.val() !== 'global' ? true : false;
      $field.toggleClass('has-value', hasValue);
    }

    function updateTemplates(templateId) {
      jupiterx.elementor.getTemplates({
        data: {
          type: settings.templateType
        },
        beforeSend: function beforeSend() {
          $select.empty();
          $field.addClass('is-loading');
          $select.append('<option value selected>Loading...</option>');
        },
        success: function success(templates) {
          $select.empty();
          $field.removeClass('is-loading');

          if (settings.global) {
            $select.append('<option value="global" selected>' + settings.global + '</option>');
          }

          if (templates) {
            for (var id in templates) {
              var selected = parseInt(templateId) === parseInt(id) ? 'selected' : '';
              $select.append('<option ' + selected + ' value="' + id + '">' + templates[id] + '</option>');
            }
          }

          $select.trigger('change');
        }
      });
    }

    $editButton.click(function (event) {
      event.preventDefault();

      if (typeof jupiterx.elementor === 'undefined') {
        return;
      }

      jupiterx.elementor.openEditor({
        action: 'edit',
        post: $select.val(),
        beforeClose: function beforeClose(contentWindow) {
          var status = contentWindow.elementor.channels.editor.request('status');

          if (contentWindow.elementor.config.document.id) {
            updateTemplates(contentWindow.elementor.config.document.id);
          }

          if (status === false) {
            $select.trigger('change');
          } else if (status === true && !confirm('Are you sure you want to discard the changes?')) {
            return false;
          }
        }
      });
    });

    $newButton.click(function (event) {
      event.preventDefault();

      if (typeof jupiterx.elementor === 'undefined') {
        return;
      }

      jupiterx.elementor.openEditor({
        action: 'new',
        type: settings.templateType,
        beforeClose: function beforeClose(contentWindow) {
          if (contentWindow.elementor.config.document.id) {
            updateTemplates(contentWindow.elementor.config.document.id);
          }
        }
      });
    });

    $select.on('change', updateHasValue);
    updateHasValue();
  }

  /**
   * Button group field.
   *
   * @since 1.3.0
   */
  acf.add_action('ready_field/type=button_group', function ($field) {
    var options = acf.getField($field);

    if (!options.data.proChoices) {
      return;
    }

    $.each(options.data.proChoices, function (index, choice) {
      var $label = $field.find('input[value=' + choice + ']').parent('label');

      $label.append(jupiterxUtils.proBadge).on('click', function (event) {
        event.preventDefault();
      });
    });
  });
})(jQuery);