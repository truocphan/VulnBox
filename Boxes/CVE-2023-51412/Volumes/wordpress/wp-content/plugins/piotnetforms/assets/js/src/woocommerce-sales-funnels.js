import './lib/jquery-throttle.js';

jQuery(document).ready(function($) {

	$(document).on('keyup change','[data-piotnetforms-id]', $.debounce( 700, function(){
		var formID = $(this).attr('data-piotnetforms-id'); 
		piotnetformsWoocommerceCheckout(formID);
	})
	);

	$(window).on('load', function(){
		piotnetformsWoocommerceCheckout();
	});

	function piotnetformsWoocommerceCheckout(formID) {
		if (formID == '') {
			var $piotnetformsWoocommerceCheckout = $(document).find('[data-piotnetforms-woocommerce-checkout-form-id]');
		} else {
			var $piotnetformsWoocommerceCheckout = $(document).find('[data-piotnetforms-woocommerce-checkout-form-id="' + formID + '"]');
		}
		
		if ($piotnetformsWoocommerceCheckout.length > 0) {
			$piotnetformsWoocommerceCheckout.each(function(){
		    	var formID = $(this).attr('data-piotnetforms-woocommerce-checkout-form-id'),
		    		$fields = $(document).find('[data-piotnetforms-id='+ formID +']'),
		    		fieldsOj = [],
		    		formData = new FormData();

				var $submit = $(this);
				var $parent = $submit.closest('.piotnetforms-fields-wrapper');

				$fields.each(function(){
					if ( $(this).data('piotnetforms-stripe') == undefined && $(this).data('piotnetforms-html') == undefined ) {
						var $checkboxRequired = $(this).closest('.piotnetforms-field-type-checkbox.piotnetforms-field-required');
						var checked = 0;
						if ($checkboxRequired.length > 0) {
							checked = $checkboxRequired.find("input[type=checkbox]:checked").length;
						} 

						$(this).closest('.piotnetforms-field-group').find('[data-piotnetforms-required]').html('');

						var fieldType = $(this).attr('type'),
							fieldName = $(this).attr('name');

						var $repeater = $(this).closest('[data-piotnetforms-repeater-form-id]'),
							repeaterID = '',
							repeaterIDOne = '',
							repeaterLabel = '',
							repeaterIndex = -1,
							repeaterLength = 0;

						if ($repeater.length > 0) {
							var $repeaterParents = $(this).parents('[data-piotnetforms-repeater-form-id]');
							repeaterIDOne = $repeater.data('piotnetforms-repeater-id');
							$repeaterParents.each(function(){
								var repeaterParentID = $(this).data('piotnetforms-repeater-id'),
									$repeaterParentAll = $(document).find('[data-piotnetforms-repeater-form-id="' + formID + '"][data-piotnetforms-repeater-id="' + repeaterParentID + '"]');

								var repeaterParentIndex = $(this).index() - $repeaterParentAll.index();
								repeaterID += repeaterParentID + '|index' + repeaterParentIndex + '|' + fieldName.replace('[]','').replace('form_fields[','').replace(']','') + ',';
							});

							repeaterLabel = $repeater.data('piotnetforms-repeater-label');

							var $repeaterAll = $(document).find('[data-piotnetforms-repeater-id="' + $repeater.data('piotnetforms-repeater-id') + '"]');
							repeaterLength = $repeater.siblings('[data-piotnetforms-repeater-id="' + $repeater.data('piotnetforms-repeater-id') + '"]').length + 1; 

							repeaterIndex = $repeater.index() - $repeaterAll.index();
						}

						if (fieldName.indexOf('[]') !== -1) {
		                    var fieldValueMultiple = [];

		                    if (fieldType == 'checkbox') {
		                        $(this).closest('.piotnetforms-fields-wrapper').find('[name="'+ fieldName + '"]:checked').each(function () {
		                            fieldValueMultiple.push($(this).val());
		                        }); 
		                    } else {
		                        fieldValueMultiple = $(this).val();
		                        if (fieldValueMultiple == null) {
		                            var fieldValueMultiple = [];
		                        }
		                    }

		                    fieldValue = '';
		                    var fieldValueByLabel = '';

		                    var fieldBooking = [];

		                    for (var j = 0; j < fieldValueMultiple.length; j++) {
		                    	if ($(this).data('piotnetforms-send-data-by-label') != undefined) {
		                    		var fieldValueSelected = fieldValueMultiple[j];

		                    		if (fieldType == 'checkbox') {
			                    		var $optionSelected = $(this).closest('.piotnetforms-fields-wrapper').find('[value="' + fieldValueSelected + '"]');
			                			if ($optionSelected.length > 0) {
			                				fieldValueByLabel += $optionSelected.data('piotnetforms-send-data-by-label') + ',';
			                			}
		                			} else {
		                				var $optionSelected = $(this).find('[value="' + fieldValueSelected + '"]');
			                			if ($optionSelected.length > 0) {
			                				fieldValueByLabel += $optionSelected.html() + ',';
			                			}
		                			}
		                		}

		                		fieldValue += fieldValueMultiple[j] + ',';

		                		if ($(this).attr('data-piotnetforms-form-booking-item-options') != undefined) {
		                			var fieldValueSelected = fieldValueMultiple[j];
		                			
		                			var $optionSelected = $(this).closest('.piotnetforms-fields-wrapper').find('[value="' + fieldValueSelected + '"]');
		                			if ($optionSelected.length > 0) {
	                					fieldBooking.push($optionSelected.attr('data-piotnetforms-form-booking-item-options'));  
		                			}
                				}
		                    }

		                    fieldValue = fieldValue.replace(/,(\s+)?$/, '');
						} else {
							if (fieldType == 'radio' || fieldType == 'checkbox') {
								if ($(this).data('piotnetforms-send-data-by-label') != undefined) {
									var fieldValueByLabel = $(this).closest('.piotnetforms-fields-wrapper').find('[name="'+ fieldName +'"]:checked').data('piotnetforms-send-data-by-label');
								}

								var fieldValue = $(this).closest('.piotnetforms-fields-wrapper').find('[name="'+ fieldName +'"]:checked').val();
			                } else {
			                	if ($(this).data('piotnetforms-calculated-fields') != undefined) {
			                		var fieldValue = $(this).siblings('.piotnetforms-calculated-fields-form').text();
			                	} else {
			                		if ($(this).data('piotnetforms-send-data-by-label') != undefined) {
			                			var fieldValueSelected = $(this).val().trim();
			                			var $optionSelected = $(this).find('[value="' + fieldValueSelected + '"]');
			                			if ($optionSelected.length > 0) {
			                				fieldValueByLabel = $optionSelected.html();
			                			}
			                		}

			                		var fieldValue = $(this).val().trim();
			                	}
			                }
						}
						
						if (fieldValue != undefined) {
							var fieldItem = {};
							fieldItem['label'] = $(this).closest('.piotnetforms-field-group').find('.piotnetforms-field-label').html();
							fieldItem['name'] = fieldName.replace('[]','').replace('form_fields[','').replace(']','');
							fieldItem['value'] = fieldValue;

							if (typeof fieldBooking !== 'undefined' && fieldBooking.length > 0) {
							    fieldItem['booking'] = fieldBooking;
							} 

							if (fieldValueByLabel != '') { 
								fieldItem['value_label'] = fieldValueByLabel;
							}
							
							if ($(this).closest('.piotnetforms-field-type-calculated_fields').length > 0) {
								fieldItem['calculation_results'] = $(this).val().trim();
							}
							
							if (!$(this).closest('.piotnetforms-widget').hasClass('piotnetforms-conditional-logic-hidden')) {
								fieldItem['repeater_id'] = repeaterID;
								fieldItem['repeater_id_one'] = repeaterIDOne;
								fieldItem['repeater_label'] = repeaterLabel;
								fieldItem['repeater_index'] = repeaterIndex; 
								fieldItem['repeater_length'] = repeaterLength;

							    fieldsOj.push(fieldItem); 
							}
						}						
					}
				});

				formData.append("action", "piotnetforms_ajax_form_builder_woocommerce_checkout");
				formData.append("fields", JSON.stringify(fieldsOj)); 
				formData.append("form_id", $(this).attr('data-piotnetforms-woocommerce-checkout-id'));
				formData.append("post_id", $(this).attr('data-piotnetforms-woocommerce-checkout-post-id'));
				formData.append("product_id", $(this).attr('data-piotnetforms-woocommerce-checkout-product-id'));

				$.ajax({
					url: $('[data-piotnetforms-ajax-url]').data('piotnetforms-ajax-url'),
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function (response) {
						if (response.trim() == '1') {
							$( 'body' ).trigger( 'update_checkout' );
							$( 'body' ).trigger( 'wc_update_cart' );
						}
					}
				});
			});
		}
		
	}
	 
	$(document).on( 'click', '[data-piotnetforms-woocommerce-sales-funnels-add-to-cart]', function(e){
		e.preventDefault();
		var $button = $(this);
		$button.css('opacity','0.5');
		$button.find('[data-piotnetforms-woocommerce-sales-funnels-add-to-cart-message]').remove();

		var optionsJSON = $(this).attr('data-piotnetforms-woocommerce-sales-funnels-add-to-cart'),
			options = JSON.parse(optionsJSON);

		$.ajax({
			url: $('[data-piotnetforms-ajax-url]').data('piotnetforms-ajax-url'),
			type: 'POST',
			data: {
				'action': 'piotnetforms_ajax_woocommerce_sales_funnels_add_to_cart',
				'options': options,
			},
			success: function (response) {
				$button.css('opacity','1');
				var responseObj = JSON.parse(response);

				if (responseObj.status == 1) {
					$button.append('<div data-piotnetforms-woocommerce-sales-funnels-add-to-cart-message class="piotnetforms-woocommerce-sales-funnels-add-to-cart-message piotnetforms-woocommerce-sales-funnels-add-to-cart-message--success">' + options['message_success'] + '</div>');
					$( 'body' ).trigger( 'update_checkout' );
					$( 'body' ).trigger( 'wc_update_cart' );
				} else {
					$button.append('<div data-piotnetforms-woocommerce-sales-funnels-add-to-cart-message class="piotnetforms-woocommerce-sales-funnels-add-to-cart-message piotnetforms-woocommerce-sales-funnels-add-to-cart-message--out-of-stock">' + options['message_out_of_stock'] + '</div>');
					$( 'body' ).trigger( 'update_checkout' );
					$( 'body' ).trigger( 'wc_update_cart' );
				}
			}
		});
	});
 
});
