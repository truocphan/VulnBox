(function($) {
		/**
		 * Insert text in input at cursor position
		 *
		 * Reference: http://stackoverflow.com/questions/1064089/inserting-a-text-where-cursor-is-using-javascript-jquery
		 */
	function insert_at_caret(input, text) {
		var txtarea = input;
		if ( ! txtarea) {
			return; }

		text          = '[' + text + ']';
		var scrollPos = txtarea.scrollTop;
		var strPos    = 0;
		var br        = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
		"ff" : (document.selection ? "ie" : false ) );
		if (br == "ie") {
			txtarea.focus();
			var range = document.selection.createRange();
			range.moveStart( 'character', -txtarea.value.length );
			strPos = range.text.length;
		} else if (br == "ff") {
			strPos = txtarea.selectionStart;
		}

		var front     = (txtarea.value).substring( 0, strPos );
		var back      = (txtarea.value).substring( strPos, txtarea.value.length );
		txtarea.value = front + text + back;
		strPos        = strPos + text.length;
		if (br == "ie") {
			txtarea.focus();
			var ieRange = document.selection.createRange();
			ieRange.moveStart( 'character', -txtarea.value.length );
			ieRange.moveStart( 'character', strPos );
			ieRange.moveEnd( 'character', 0 );
			ieRange.select();
		} else if (br == "ff") {
			txtarea.selectionStart = strPos;
			txtarea.selectionEnd   = strPos;
			txtarea.focus();
		}

		txtarea.scrollTop = scrollPos;
	}

	$( document ).ready(
		function() {

			$( '.select2' ).select2(
				{
					closeOnSelect: false
				}
			);

			$( document ).on(
				'change',
				'.dynamic-values select',
				function(e) {

					e.stopPropagation();

					var $option = $( this );

					var value = $option.val();

					if ( value == '' ) {
						return;
					}

					var $editor = $option.parents( '.acf-field[data-dynamic_values]' ).first().find( '.wp-editor-area' );

					// Check if we should insert into WYSIWYG field or a regular field
					if ( $editor.length > 0 ) {

						// WYSIWYG field
						var editor = tinymce.editors[ $editor.attr( 'id' ) ];
						editor.editorCommands.execCommand( 'mceInsertContent', false, '[' + value + ']' );
						$dvOpened = false;

					} else {

						// Regular field
						var $input = $option.parents( '.dynamic-values' ).siblings( 'input[type=text]' );
						insert_at_caret( $input.get( 0 ), value );

					}

					$option.removeProp( 'selected' ).closest( 'select' ).val( '' ).trigger( 'change' );

				}
			);

			// Toggle dropdown
			$( document ).on(
				'focusin click',
				'.acf-field[data-dynamic_values] input, a.dynamic-value-options',
				function(e) {
					e.stopPropagation();

					var $this = $( this );
					// dynamicValues.find('.all_fields-option').addClass('acf-hidden');
					var $dynamicValues = $( '.dynamic-values' );
					$this.after( $dynamicValues );
					$dynamicValues.show();
				}
			);

	
			$( 'body' ).on(
				'change',
				'select#form-admin_form_type',
				function(e){
					var title = $( this ).parents( 'form' ).find( 'input#title' );

					if ( title.val() == '' ) {
						title.val( $( this ).find( 'option[value=' + $( this ).val() + ']' ).text() );
						title.siblings( 'label' ).addClass( 'screen-reader-text' );
					}
				}
			);
		

		}
	);


})( jQuery );

