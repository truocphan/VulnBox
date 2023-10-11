jQuery(document).ready( function($) {
	$(document).on( "change", "#expmm", function(e) {
		$("input[name='expmm']").val($("#expmm option:selected").val());
	});
	$(document).on( "change", "#expyy", function(e) {
		$("input[name='expyy']").val($("#expyy option:selected").val());
	});
	if ( 'on' == escott_params.sec3d_activate ) {
		if( !$("#memberinfo").length ) {
			$('body input[type="submit"]').each( function(i,e) {
				if( "editmember" == $(this).attr("name") ) {
					$(this).parents("form").attr("id","memberinfo");
				}
			});
		}
		var agree_dialog = '<div id="escott-agree-dialog">'
			+'<textarea class="escott_agreement_message" readonly>'+escott_params.message.agreement+'</textarea>'
			+'<div class="send agree_form_send">'
				+'<input type="button" id="escott_agree_cancel" class="back_to_delivery_button" value="'+escott_params.message.disagree+'" />&nbsp;&nbsp;'
				+'<input type="button" id="escott_agree_next" class="to_confirm_button" value="'+escott_params.message.agree+'" />'
			+'</div>'
		+'</div><input type="hidden" id="escott_agree" value=""><input type="hidden" id="escott_settlement_mode" value="">';
		$("#memberinfo").after(agree_dialog);
		$("#escott-agree-dialog .send").css("text-align","center");
		$("#escott-agree-dialog").css("display","none");
	}
});
jQuery( function($) {
	memberEScott = {
		getToken: function() {
			if( $("#register").val() != undefined ) {
				var check = true;
				if( "" == $("#cardno").val() ) {
					check = false;
				}
				if( undefined == $("#expyy").get(0) || undefined == $("#expmm").get(0) ) {
					check = false;
				} else if( "" == $("#expyy option:selected").val() || "" == $("#expmm option:selected").val() ) {
					check = false;
				}
				if( $("#seccd").val() != undefined ) {
					if( "" == $("#seccd").val() ) {
						check = false;
					}
				}
				if( !check ) {
					alert(uscesL10n.escott_token_error_message);
					return false;
				}
			}

			var cardno = $("#cardno").val();
			var expyy = $("#expyy option:selected").val();
			if( "" != expyy ) {
				expyy = expyy.substr(-2,2);
			}
			var expmm = $("#expmm option:selected").val();
			var seccd = ( $("#seccd").val() != undefined ) ? $("#seccd").val() : "";

			SpsvApi.spsvCreateToken(cardno,expyy,expmm,seccd,"","","","","");
		}
	};

	$(document).on( "click", "#card-register", function(e) {
		if( $("#token").val() != undefined ) {
			memberEScott.getToken();
		} else {
			$("#member-card-info").submit();
		}
	});

	$(document).on( "click", "#card-update", function(e) {
		if( $("#token").val() != undefined ) {
			if( "" == $("#cardno").val() ) {
				$("#member-card-info").submit();
			} else {
				memberEScott.getToken();
			}
		} else {
			$("#member-card-info").submit();
		}
	});

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
		if ( $("#escott_settlement_mode").val() == "update" ) {
			location.href = decodeURIComponent(escott_params.update_settlement_url);
		} else {
			location.href = decodeURIComponent(escott_params.register_settlement_url);
		}
		return false;
	});

	$(document).on( "click", "#escott_agree_cancel", function(e) {
		$("#escott_agree").val("");
		$("#escott-agree-dialog").dialog("close");
	});

	$(document).on( "click", ".escott_agreement", function(e) {
		e.preventDefault();
		e.stopPropagation();
		$("#escott_settlement_mode").val($(this).attr("data-mode"));
		$("#escott-agree-dialog").dialog("open");
		return false;
	});
});

function setToken(token,card) {
	if( token ) {
		document.getElementById("token").value = token;
		document.getElementById("member-card-info").submit();
	}
}
