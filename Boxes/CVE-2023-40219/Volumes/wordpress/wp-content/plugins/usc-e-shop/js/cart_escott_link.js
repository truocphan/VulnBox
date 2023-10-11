jQuery(document).ready( function($) {
	$('body input[type="submit"]').each( function(i,e) {
		if( "confirm" == $(this).attr("name") ) {
			$(this).parents("form").attr("id","delivery-form");
			$(this).parents("form").attr("name","delivery_form");
		}
	});
	if ( 'link' == escott_params.card_activate ) {
		var agree_dialog = '<div id="escott-agree-dialog">'
			+'<textarea class="escott_agreement_message" readonly>'+escott_params.message.agreement+'</textarea>'
			+'<div class="send agree_form_send">'
				+'<input type="button" id="escott_agree_cancel" class="back_to_delivery_button" value="'+escott_params.message.disagree+'" />&nbsp;&nbsp;'
				+'<input type="button" id="escott_agree_next" class="to_confirm_button" value="'+escott_params.message.agree+'" />'
			+'</div>'
		+'</div><input type="hidden" id="escott_agree" value="">';
		// $("#delivery-form").after(agree_dialog);
		$(agree_dialog).appendTo($("#delivery-form"));
		$("#escott-agree-dialog .send").css("text-align","center");
		$("#escott-agree-dialog").css("display","none");
	}
});
jQuery( function($) {
	$("#escott-agree-dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: "400",
		width: "200",
		resizable: true,
		modal: true,
		create: function() {
			$("#escott-agree-dialog").parent(".ui-dialog").attr("id","escott-dialog");
		},
		open: function() {
		},
		close: function() {
		}
	});

	$(document).on( "click", "#escott_agree_next", function(e) {
		$("#escott_agree").val("agree");
		$("#escott-agree-dialog").dialog("close");
		var element = document.createElement("input");
		element.setAttribute("type","hidden");
		element.setAttribute("name","confirm");
		element.setAttribute("value","confirm");
		document.delivery_form.appendChild(element);
		document.delivery_form.submit();
		return true;
	});

	$(document).on( "click", "#escott_agree_cancel", function(e) {
		$("#escott_agree").val("");
		$("#escott-agree-dialog").dialog("close");
	});

	$(document).on( "click", ".to_confirm_button", function(e) {
		if( $("#payment_name_"+uscesL10n.escott_link_payment_id).prop("checked") && 'link' == escott_params.card_activate ) {
			if ( "agree" == $("#escott_agree").val() ) {
				// $("#delivery-form").submit();
				// return true;
			} else {
				e.preventDefault();
				e.stopPropagation();
				$("#escott-agree-dialog").dialog("open");
				return false;
			}
		}
	});
});
