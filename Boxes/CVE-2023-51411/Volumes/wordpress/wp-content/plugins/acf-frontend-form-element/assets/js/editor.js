(function($) {
	$( 'body' ).on(
		'click',
		'.sub-fields-close',
		function() {
			$( this ).removeClass( 'sub-fields-close' ).addClass( 'sub-fields-open' );
			removePopup( type );
		}
	);

	$( 'body' ).on(
		'click',
		'.new-fea-form',
		function(event) {
			$link = $( this ).data( 'link' );

			window.open( $link, '_blank' );
		}
	);
	$( 'body' ).on(
		'click',
		'.edit-fea-form',
		function(event) {
			event.stopPropagation();
			var $form = $( this ).parents( '.elementor-control' ).siblings( '.elementor-control-admin_forms_select' ).find( 'select[data-setting=admin_forms_select]' ).val();
			$link     = $( this ).data( 'link' );

			window.open( $link + '?post=' + $form + '&action=edit', '_blank' );
		}
	);

	$( 'body' ).on(
		'click',
		'.sub-fields-open',
		function(event) {
			event.stopPropagation();
			type      = $( this ).data( 'type' );
			var popup = $( '<div class="sub-fields-container popup_' + type + '"><button class="add-sub-field" type="button"><i class="eicon-plus" aria-hidden="true"></i></button></div>' );

			$parent_section = $( this ).parents( '.elementor-control-fields_selection' );

			$( this ).after( popup );

			$subfields_section = $parent_section.siblings( '.elementor-control-' + type + '_fields' );

			$subfields_section.css( 'display','block' );

			popup.prepend( $subfields_section );

			$( this ).removeClass( 'sub-fields-open' ).addClass( 'sub-fields-close' );

		}
	);

	function removePopup(type){
		var $popup         = $( '.popup_' + type );
		$subfields_section = $popup.find( '.elementor-control-' + type + '_fields' );
		$subfields_section.css( 'display','none' );

		$parent_section.after( $subfields_section );
		$popup.remove();
	}

	$( 'body' ).on(
		'click',
		'.add-sub-field',
		function() {
			var repeaterWrapper = $subfields_section.find( '.elementor-repeater-fields-wrapper' );
			repeaterWrapper.find( '.elementor-repeater-fields:last-child' ).find( '.elementor-repeater-tool-duplicate' ).click();

			var newField = repeaterWrapper.find( '.elementor-repeater-fields:last-child' );
			// newField.find('.elementor-control:gt(1)').addClass('elementor-hidden-control');
			newField.find( 'input[data-setting="field_label_on"]' ).val( 'true' ).change();
			var fieldType = newField.find( 'select[data-setting="field_type"]' );
			fieldType.val( 'description' ).change();
			newField.find( 'input[data-setting="label"]' ).val( fieldType.find( 'option[value="description"]' ).text() ).change().trigger( 'input' );

		}
	);
})( jQuery );
