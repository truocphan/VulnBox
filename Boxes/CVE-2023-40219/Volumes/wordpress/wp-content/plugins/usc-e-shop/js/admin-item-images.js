// JavaScript
jQuery(function ($) {
	setTimeout(removeLoaderBoxImg, 1000); //wait for page load PLUS 1 second.
	$(window).on('load', function () {
		removeLoaderBoxImg();
	});
	function removeLoaderBoxImg(){
		$('#item-main-pict #wel-item-image-loading').hide();
		$('#item-main-pict #uscestabs_item_images').show();
	};
	$("#uscestabs_item_images").tabs({
		active: 0,
	});
	// load popup show message.
	$("#wel_item_image_dialog_wrap").dialog({
		autoOpen: false,
		height: 200,
		width: 500,
		modal: true,
		buttons: [{
			text: usces_item_images_js_setting.text_close,
			click: function () {
				$(this).dialog('close');
			}
			}],
		close: function () {
			$("#wel_item_image_dialog_content").html('');
		}
	});
	$('#uscestabs_item_images input#wel_item_img_media_manager').click(function (e) {
		e.preventDefault();
		var image_frame;
		if (image_frame) {
			image_frame.open();
		}
		// Define image_frame as wp.media object
		image_frame = wp.media({
			title: usces_item_images_js_setting.title_image_frame,
			multiple: true,
			library: {
			type: 'image'
			}
		});

		image_frame.on('select', function () {
			// On select, get selections and save to the hidden input
			// plus other AJAX stuff to refresh the image preview
			var selection = image_frame.state().get('selection');
			var gallery_ids = new Array();
			var my_index = 0;
			selection.each(function (attachment) {
				gallery_ids[my_index] = attachment['id'];
				my_index++;
			});
			var ids = gallery_ids.join(",");
			wel_item_images.choose_images_from_media(ids);
		});
		image_frame.on('close',function() {
			// handel load image alt, caption in case if have any change on the media.
			var pict_ids = $("#uscestabs_item_images #wel-img-pict-ids").val();
			if (pict_ids) {
				var ar_pict_ids = pict_ids.split(",");
				if (0 == ar_pict_ids.length) {
					return false;
				}
				var queryargs = {post__in: ar_pict_ids};
				wp.media.query(queryargs) // set the query
					.more() // execute the query, this will return an deferred object 
					.done(function(){ // attach callback, executes after the ajax call succeeded
					var attachments = this.toJSON();
					if (attachments) {
						$.each(attachments, function( index, value ) {
							var pict_id = value.id;
							var pict_alt = value.alt;
							var pict_caption = value.caption;
							// load info for main pict.
							var main_pict_id = $("#uscestabs_item_img #wel-img-pict-id").val();
							if (main_pict_id == pict_id) {
								$("#uscestabs_item_img #wel-img-alt").val(pict_alt);
								$("#uscestabs_item_img #wel-img-caption").val(pict_caption);
							}
							// load info for sub pict.
							$("#uscestabs_item_img subpict#wel-pict-"+pict_id).attr('data-alt', pict_alt);
							$("#uscestabs_item_img subpict#wel-pict-"+pict_id).attr('data-caption', pict_caption);
						});
					}
				});
			}
		});

		image_frame.open();
	});
	
	$("body #uscestabs_item_images").on("click",".wel-view-main-img", function(){
		var elm_img = $(this);
		wel_item_images.change_item_pict( elm_img );
	});

	$("#uscestabs_item_file #wrapper_tab_file_item_pict").sortable({
		update: function () {
			var item_pict_ids = $("#wrapper_tab_file_item_pict").sortable("toArray");
			wel_item_images.sort_order_item_image(item_pict_ids);
		}
	});
	$("#uscestabs_item_file #wrapper_tab_file_item_pict").disableSelection();

	wel_item_images = {
		settings: {
			url: uscesL10n.requestFile,
			type: 'POST',
			cache: false
		},
		choose_images_from_media: function (ids) {
			var post_id = $('#uscestabs_item_images #wel_image_post_id').val();
			var s = Object.assign({}, wel_item_images.settings);
			s.data = {
				'action': 'wel_item_image_ajax',
				'mode': 'choose_images_from_media',
				'post_id': post_id,
				'str_pict_ids': ids,
				'_ajax_nonce': usces_item_images_js_setting._ajax_nonce
			};

			// add loading.
			$("#item-main-pict #wel-tab-file-loading").show();
			$.ajax(s).done(function (res) {
console.log(res);
				// remove effect loading.
				$("#item-main-pict #wel-tab-file-loading").hide();
				if (res.status) {
					// Load list on the tab file.
					$('#uscestabs_item_images #uscestabs_item_file #wrapper_tab_file_item_pict').html(res.data_tab_file);
					// Load list on the tab image.
					$('#uscestabs_item_images #uscestabs_item_img').html(res.data_tab_image);
					// active tab file.
					$("#uscestabs_item_images #usces_tabs_item_file").click();
					// add more scoll.
					$("#uscestabs_item_file #wrapper_tab_file_item_pict").animate({ scrollTop: $("#uscestabs_item_file #wrapper_tab_file_item_pict")[0].scrollHeight}, 700);
				} else if ('' != res.msg) {
					$("#wel_item_image_dialog_content").html(res.msg);
					$("#wel_item_image_dialog_wrap").dialog("open");
				}
			}).fail(function (msg) {
				// remove loading.
				$("#item-main-pict #wel-tab-file-loading").hide();
				$("#wel_item_image_dialog_content").html(usces_item_images_js_setting.msg_nonce_expried);
				$("#wel_item_image_dialog_wrap").dialog("open");
			});
		},
		sort_order_item_image: function (item_pict_ids) {
			var post_id = $('#uscestabs_item_images #wel_image_post_id').val();
			if (0 < item_pict_ids.length) {
			var s = Object.assign({}, wel_item_images.settings);
			s.data = {
				'action': 'wel_item_image_ajax',
				'mode': 'sort_order_item_image',
				'post_id': post_id,
				'item_pict_ids': item_pict_ids,
				'_ajax_nonce': usces_item_images_js_setting._ajax_nonce
			};
			$("#item-main-pict #item-select-pict").addClass('div-item-img-loading');
			$.ajax(s).done(function (res) {
				$("#item-main-pict #item-select-pict").removeClass('div-item-img-loading');
				if (res.status) {
					// Load list on the tab file.
					$('#uscestabs_item_images #uscestabs_item_file #wrapper_tab_file_item_pict').html(res.data_tab_file);
					// Load list on the tab image.
					$('#uscestabs_item_images #uscestabs_item_img').html(res.data_tab_image);
				} else if ('' != res.msg) {
					$("#wel_item_image_dialog_content").html(res.msg);
					$("#wel_item_image_dialog_wrap").dialog("open");
				}
			}).fail(function (msg) {
				$("#item-main-pict #item-select-pict").removeClass('div-item-img-loading');
				$("#wel_item_image_dialog_content").html(usces_item_images_js_setting.msg_nonce_expried);
				$("#wel_item_image_dialog_wrap").dialog("open");
			});
			}
		},
		exclude_item_image: function () {
			var post_id = $('#uscestabs_item_images #wel_image_post_id').val();
			var item_pict_ids = [];
			$("body #uscestabs_item_file input[name*='file_item_picts']:checked").each(function () {
				item_pict_ids.push($(this).val());
			});
			if (0 == item_pict_ids.length) {
				// show popup message.
				$("#wel_item_image_dialog_content").text(usces_item_images_js_setting.msg_choose_item_image);
				$("#wel_item_image_dialog_wrap").dialog("open");
			} else {
				var img_loading = '<div id="wrap_img_icon_loading"><img src="' + uscesL10n.USCES_PLUGIN_URL + 'images/loading.gif" /></div>';
				$("#uscestabs_item_file #wel_file_exclude").prop("disabled", true);
				$(img_loading).insertBefore("#uscestabs_item_file #wel_file_exclude");

				var s = Object.assign({}, wel_item_images.settings);
				s.data = {
					'action': 'wel_item_image_ajax',
					'mode': 'exclude_item_image',
					'post_id': post_id,
					'item_pict_ids': item_pict_ids,
					'_ajax_nonce': usces_item_images_js_setting._ajax_nonce
				};
				$.ajax(s).done(function (res) {
					$("#wrap_img_icon_loading").remove();
					$("#uscestabs_item_file #wel_file_exclude").removeAttr('disabled');
					if (res.status) {
						// Load list on the tab file.
						$('#uscestabs_item_images #uscestabs_item_file #wrapper_tab_file_item_pict').html(res.data_tab_file);
						// Load list on the tab image.
						$('#uscestabs_item_images #uscestabs_item_img').html(res.data_tab_image);
					} else if ('' != res.msg) {
						$("#wel_item_image_dialog_content").html(res.msg);
						$("#wel_item_image_dialog_wrap").dialog("open");
					}
				}).fail(function (msg) {
					$("#wrap_img_icon_loading").remove();
					$("#uscestabs_item_file #wel_file_exclude").removeAttr('disabled');
					$("#wel_item_image_dialog_content").html(usces_item_images_js_setting.msg_nonce_expried);
					$("#wel_item_image_dialog_wrap").dialog("open");
				});
			}
		},
		check_before_delete_item_image: function () {
			var post_id = $('#uscestabs_item_images #wel_image_post_id').val();
			var pict_ids = [];
			$("body #uscestabs_item_file input[name*='file_item_picts']:checked").each(function () {
				pict_ids.push($(this).val());
			});
			if (0 == pict_ids.length) {
				// show popup message.
				$("#wel_item_image_dialog_content").text(usces_item_images_js_setting.msg_choose_item_image);
				$("#wel_item_image_dialog_wrap").dialog("open");
			} else {
				var img_loading = '<div id="wrap_img_icon_loading"><img src="' + uscesL10n.USCES_PLUGIN_URL + 'images/loading.gif" /></div>';
				$("#uscestabs_item_file #wel_file_delete").prop("disabled", true);
				$(img_loading).insertAfter("#uscestabs_item_file #wel_file_delete");

				var s = Object.assign({}, wel_item_images.settings);
				s.data = {
					'action': 'wel_item_image_ajax',
					'mode': 'validate_item_image_before_delete',
					'post_id': post_id,
					'item_pict_ids': pict_ids,
					'_ajax_nonce': usces_item_images_js_setting._ajax_nonce
				};
				$.ajax(s).done(function (res) {
					if (res.status) {
					if (res.check_in_other_product) {
						// show confirm.
						if (confirm(res.msg_show_confirm)) {
							wel_item_images.delete_item_image(res.none_item_delete);
						}  else {
							$("#wrap_img_icon_loading").remove();
							$("#uscestabs_item_file #wel_file_delete").removeAttr('disabled');
						}
					} else {
						// continue run delete item.
						wel_item_images.delete_item_image(res.none_item_delete);
					}
					}
				}).fail(function (msg) {
					$("#wel_item_image_dialog_content").html(usces_item_images_js_setting.msg_nonce_expried);
					$("#wel_item_image_dialog_wrap").dialog("open");
					$("#wrap_img_icon_loading").remove();
					$("#uscestabs_item_file #wel_file_delete").removeAttr('disabled');
				});
			}
		},
		delete_item_image: function (ajax_nonce) {
			var post_id = $('#uscestabs_item_images #wel_image_post_id').val();
			var pict_ids = [];
			$("body #uscestabs_item_file input[name*='file_item_picts']:checked").each(function () {
				pict_ids.push($(this).val());
			});
			if (0 == pict_ids.length) {
				// show popup message.
				$("#wel_item_image_dialog_content").text(usces_item_images_js_setting.msg_choose_item_image);
				$("#wel_item_image_dialog_wrap").dialog("open");
			} else {
				var s = Object.assign({}, wel_item_images.settings);
				s.data = {
					'action': 'wel_item_image_ajax',
					'mode': 'delete_item_image',
					'post_id': post_id,
					'item_pict_ids': pict_ids,
					'_ajax_nonce': ajax_nonce
				};
				$.ajax(s).done(function (res) {
					$("#wrap_img_icon_loading").remove();
					$("#uscestabs_item_file #wel_file_delete").removeAttr('disabled');
					if (res.status) {
						// Load list on the tab file.
						$('#uscestabs_item_images #uscestabs_item_file #wrapper_tab_file_item_pict').html(res.data_tab_file);
						// Load list on the tab image.
						$('#uscestabs_item_images #uscestabs_item_img').html(res.data_tab_image);
					} else if ('' != res.msg) {
						$("#wel_item_image_dialog_content").html(res.msg);
						$("#wel_item_image_dialog_wrap").dialog("open");
					}
				}).fail(function (msg) {
					$("#wel_item_image_dialog_content").html(usces_item_images_js_setting.msg_nonce_expried);
					$("#wel_item_image_dialog_wrap").dialog("open");
					$("#wrap_img_icon_loading").remove();
					$("#uscestabs_item_file #wel_file_delete").removeAttr('disabled');
				});
			}
		},
		change_item_pict: function ( elm_img ) {
			$("#uscestabs_item_img #item-select-pict").html(elm_img.attr('data-img'));
			$("#uscestabs_item_img #wel-img-alt").val(elm_img.attr('data-alt'));
			$("#uscestabs_item_img #wel-img-caption").val(elm_img.attr('data-caption'));
			$("#uscestabs_item_img #wel-img-pict-id").val(elm_img.attr('data-id'));
		},
		save_pict_info: function ( field ) {
			var pict_id = $("#uscestabs_item_img #wel-img-pict-id").val();
			var ol_elm = $("#uscestabs_item_img .subpict #wel-pict-" + pict_id);
			var old_val = ol_elm.attr('data-' + field);
			var elm_field = $("#uscestabs_item_img #wel-img-" + field);
			var val = elm_field.val();
			if ( old_val !== val ) {
				elm_field.prop('disabled', true);
				var s = Object.assign({}, wel_item_images.settings);
				s.data = {
					'action': 'wel_item_image_ajax',
					'mode': 'save_pict_info',
					'pict_id': pict_id,
					'field': field,
					'value' : val,
					'_ajax_nonce': usces_item_images_js_setting._ajax_nonce
				};
				$.ajax(s).done(function (res) {
					elm_field.prop('disabled', false);
					if (res.status) {
						ol_elm.attr('data-' + field, val);
					}
				}).fail(function (msg) {
					elm_field.prop('disabled', false);
					$("#wel_item_image_dialog_content").html(usces_item_images_js_setting.msg_nonce_expried);
					$("#wel_item_image_dialog_wrap").dialog("open");
				});
			}
		}
	};
});
