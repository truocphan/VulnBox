jQuery(document).ready(function($) {

	function clickSubmit(submitButton) {
		var $this = submitButton;
		var formID = $this.data('piotnetforms-submit-form-id'),
		$fields = $(document).find('[data-piotnetforms-id='+ formID +']'),
		requiredText = $this.data('piotnetforms-required-text'),
		fieldsOj = [],
		error = 0,
		formData = new FormData();

		var $submit = $this;
		var $parent = $submit.closest('.piotnetforms-submit');

		$fields.each(function(){
			if ( $(this).data('piotnetforms-html') == undefined ) {
				var $checkboxRequired = $(this).closest('.piotnetforms-field-type-checkbox.piotnetforms-field-required');
				var checked = 0;
				if ($checkboxRequired.length > 0) {
					checked = $checkboxRequired.find("input[type=checkbox]:checked").length;
				} 

				if ($(this).attr('oninvalid') != undefined) {
					requiredText = $(this).attr('oninvalid').replace("this.setCustomValidity('","").replace("')","");
				}

				if ( !$(this)[0].checkValidity() && $(this).closest('.piotnetforms-widget').css('display') != 'none' && $(this).closest('[data-piotnetforms-conditional-logic]').css('display') != 'none' && $(this).data('piotnetforms-honeypot') == undefined &&  $(this).closest('[data-piotnetforms-signature]').length == 0 || checked == 0 && $checkboxRequired.length > 0 && $(this).closest('.piotnetforms-fields-wrapper').css('display') != 'none') {
					if ($(this).css('display') == 'none' || $(this).closest('div').css('display') == 'none' || $(this).data('piotnetforms-image-select') != undefined || $checkboxRequired.length > 0) {
						$(this).closest('.piotnetforms-field-group').find('[data-piotnetforms-required]').html(requiredText);
						
					} else {
						if ($(this).data('piotnetforms-image-select') == undefined) {
							$(this)[0].reportValidity();
						} 
					}

					error++;
				} else {

					$(this).closest('.piotnetforms-field-group').find('[data-piotnetforms-required]').html('');

					if ($(this).closest('[data-piotnetforms-signature]').length > 0) {
						var $piotnetformsSingature = $(this).closest('[data-piotnetforms-signature]'),
							$exportButton = $piotnetformsSingature.find('[data-piotnetforms-signature-export]');

						$exportButton.trigger('click');

						if ($(this).val() == '' && $(this).closest('.piotnetforms-widget').css('display') != 'none' && $(this).attr('required') != undefined) {
							$(this).closest('.piotnetforms-field-group').find('[data-piotnetforms-required]').html(requiredText);
							error++;
						} 
					}

					var fieldType = $(this).attr('type'),
						fieldName = $(this).attr('name');

					if (fieldType == 'file') { 
						if($(this).hasClass('error')) {
							error++;
						} else {

							fieldName = $(this).attr('id').replace('form-field-','');

							$.each($(this)[0].files, function(i, file){
								formData.append( fieldName + '[]', file);
							});

							var fieldItem = {};
							fieldItem['label'] = $(this).closest('.piotnetforms-field-group').find('.piotnetforms-field-label').html();
							fieldItem['name'] = fieldName;
							fieldItem['value'] = '';
							fieldItem['type'] = $(this).attr('type');
							fieldItem['upload'] = 1;

							if ($(this).data('piotnetforms-remove-this-field-from-repeater') != undefined) {
	                    		fieldItem['repeater_remove_this_field'] = '1';
                    		}

							if($(this).data('attach-files') != undefined) {
								fieldItem['attach-files'] = 1;
							}
							
							fieldsOj.push(fieldItem);

						}

						// [ Fix alert
					} else {
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

	                    		if ($(this).attr('data-piotnetforms-booking-item-options') != undefined) {
		                			var fieldValueSelected = fieldValueMultiple[j];
		                			
		                			var $optionSelected = $(this).closest('.piotnetforms-fields-wrapper').find('[value="' + fieldValueSelected + '"]');
		                			if ($optionSelected.length > 0) {
		                				console.log($optionSelected.attr('data-piotnetforms-booking-item-options'));
	                					fieldBooking.push($optionSelected.attr('data-piotnetforms-booking-item-options'));  
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
							if (fieldValueMultiple != undefined) {
								fieldItem['value_multiple'] = fieldValueMultiple;
							}
							fieldItem['type'] = $(this).attr('type');

							if (fieldValueByLabel != '') { 
								fieldItem['value_label'] = fieldValueByLabel;
							}

							fieldsOj.push(fieldItem);
						}
					}
					
				}
			}
		});

		if (error == 0) {

			$(document).find('[data-piotnetforms-id="' + formID + '"]').closest('.piotnetforms-fields-wrapper').css({'opacity' : 0.45});
			$this.closest('.piotnetforms-submit').css({'opacity' : 0.45});
			$this.closest('.piotnetforms-submit').addClass('piotnetforms-waiting');

			formData.append("action", "piotnetforms_ajax_form_builder");
			formData.append("post_id", $parent.find('input[name="post_id"]').val());
			formData.append("form_id", $parent.find('input[name="form_id"]').val());
			formData.append("fields", JSON.stringify(fieldsOj)); 
			formData.append("referrer", window.location.href);
			formData.append("remote_ip",$(document).find('input[name="remote_ip"][data-piotnetforms-hidden-form-id="'+ formID +'"]').val());

			$parent.find('.piotnetforms-message').removeClass('visible');

			$.ajax({
				url: $('[data-piotnetforms-ajax-url]').data('piotnetforms-ajax-url'),
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function (response) {
					$parent.css({'opacity' : 1});
					$parent.removeClass('piotnetforms-waiting');
					$(document).find('[data-piotnetforms-id="' + formID + '"]').closest('.piotnetforms-fields-wrapper').css({'opacity' : 1});

	        		if (response.indexOf(',') !== -1) {
						var responseArray = response.split(',');

						$parent.find('.piotnetforms-message').each(function(){
							if (responseArray[3] != '') {
				        		var html = $this.html().replace('[post_url]','<a href="' + responseArray[3] + '">' + responseArray[3] + '</a>');
				        		$this.html(html);
				        	}
						});

			        	if (responseArray[1] != '') {
			        		$parent.find('.piotnetforms-alert--mail .piotnetforms-message-success').addClass('visible');
			        		$parent.find('[data-piotnetforms-trigger-success]').trigger('click');
			        	} else {
			        		$parent.find('.piotnetforms-alert--mail .piotnetforms-message-danger').addClass('visible');
			        		$(document).find('[data-piotnetforms-id="' + formID + '"]').closest('.piotnetforms-fields-wrapper').css({'opacity' : 1});
							$this.closest('.piotnetforms-fields-wrapper').css({'opacity' : 1});
							$parent.find('[data-piotnetforms-trigger-failed]').trigger('click');

							if (responseArray[5] != '') {
				        		$parent.find('.piotnetforms-alert--mail .piotnetforms-message-danger').html(responseArray[5].replace(/###/g, ','));
				        	}
			        	}
					}
				}
			});
		}
	}

	$(document).on('click','[data-piotnetforms-submit-form-id]',function(){
		clickSubmit($(this));
    });

	// Fix Oxygen Modal
    $('.ct-modal [data-piotnetforms-submit-form-id]').click(function(){
		clickSubmit($(this));
    });

    // Fix Oxygen not full width
	$('#piotnetforms').closest('span.ct-span').css({'display':'block'});

});