jQuery(document).ready(function($){
	'use strict';
	
	$('select[name="xoo-el-sy-options[sy-popup-height-type]"]').on( 'change', function(){

		var $setting = $( '.xoo-as-setting:has(input[name="xoo-el-sy-options[sy-popup-height]"])' );

		if( $(this).val() === 'auto' ){
			$setting.hide();
		}
		else{
			$setting.show();
		}
		
	} ).trigger('change');

	$('select[name="xoo-el-gl-options[m-form-pattern]"]').on( 'change', function(){

		var $setting = $('select[name="xoo-el-gl-options[m-nav-pattern]"]');


		if( $(this).val() === 'single' ){
			$setting.find('option[value="links"]').prop( 'selected', true );
			$setting.trigger('change');
		}
	})


	$('select[name="xoo-el-sy-options[sy-popup-style]"]').on( 'change', function(){

		var $setting = $( '.xoo-as-setting:has(select[name="xoo-el-sy-options[sy-popup-height-type]"]), .xoo-as-setting:has(input[name="xoo-el-sy-options[sy-popup-height]"])' );

		if( $(this).val() === 'slider' ){
			$setting.hide();
		}
		else{
			$setting.show();
		}
		
	} ).trigger('change');


});
