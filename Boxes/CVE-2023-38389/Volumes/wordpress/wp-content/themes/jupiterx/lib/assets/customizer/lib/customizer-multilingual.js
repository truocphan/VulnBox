/**
 *
 */
/* global wp, jQuery */
/* exported PluginCustomizer */
var JupiterXCustomizerMultilingual = (function( api, $ ) {
	'use strict';

	var component = {
		data: {
			url: null,
			languages: null,
			current_language: null,
		}
	};

	/**
	 * Initialize functionality.
	 *
	 * @param {object} args Args.
	 * @param {string} args.url  Preview URL.
	 * @returns {void}
	 */
	component.init = function init( jcml ) {
		_.extend(component.data, jcml );
		if (!jcml || !jcml.url || !jcml.languages || !jcml.current_language ) {
			throw new Error( 'Missing args' );
		}

		api.bind( 'ready', function(){
			api.previewer.previewUrl.set( jcml.url );

			var languages = jcml.languages;
			var current_language = jcml.current_language;
			var current_language_name = '';

			var html = '<span style="margin: 0 10px 0 40px;">' + jcml.switcher_text + '</span>';
			html += '<select id="jupiterx-language-select" style="padding: 4px 1px;">';
			for (var i = 0; i < languages.length; i++) {
				var language = languages[i];
				var selected = (language.slug === current_language) ? 'selected=""' : '';
				current_language_name = (language.slug === current_language) ? language.name.substr(0, 3) : 'Eng';
				html += '<option ' + selected + ' value="' + language.slug + '">' + language.name.substr(0, 3) + '</option>';
			}
			html += '</select>';
			$(html).prependTo('#customize-header-actions');


			$('body').on('change', '#jupiterx-language-select', function () {
				var language = $(this).val();
				var old_url = window.location.href;
				window.location.href = updateQueryStringParameter(window.location.href, 'lang', language);
			});
		});

		function updateQueryStringParameter(uri, key, value) {
			var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
			var separator = uri.indexOf('?') !== -1 ? "&" : "?";
			if (uri.match(re)) {
				return uri.replace(re, '$1' + key + "=" + value + '$2');
			} else {
				return uri + separator + key + "=" + value;
			}
		}
	};

	return component;
} ( wp.customize, jQuery ) );
