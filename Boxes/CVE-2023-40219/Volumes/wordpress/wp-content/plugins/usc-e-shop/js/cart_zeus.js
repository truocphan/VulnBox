jQuery(document).ready(function($) {
	$('body input[type="submit"]').each(function(i,elem) {
		if( "confirm" == $(this).attr("name") ) {
			$(this).parents("form").attr("id","delivery-form");
		}
	});

	$(document).on("click",'body input[type="submit"]',function(e) {
		if( "confirm" == $(this).attr("name") && $("#zeus").css("display") != "none" && "new" == $("#zeus_card_option").val() ) {
			zeusToken.getToken(function(zeus_token_response_data) {
				if( !zeus_token_response_data['result'] ) {
					alert(zeusToken.getErrorMessage(zeus_token_response_data['error_code']));
					e.preventDefault();
					e.stopPropagation();
					return false;
				} else {
					$("delivery-form").submit();
				};
			});
		} else {
			$("delivery-form").submit();
		}
	});
	$(document).on("click","input[name='offer[howpay]']",function() {
		if( '1' == $(this).val() ) {
			$("#cbrand_zeus").css("display","none");
			$("#div_zeus").css("display","none");
		} else {
			$("#cbrand_zeus").css("display","");
		}
	});

	$(document).on("change","select[name='offer[cbrand]']",function() {
		$("#div_zeus").css("display","");
		if( '1' == $(this).val() ) {
			$("#brand1").css("display","");
			$("#brand2").css("display","none");
			$("#brand3").css("display","none");
		} else if( '2' == $(this).val() ) {
			$("#brand1").css("display","none");
			$("#brand2").css("display","");
			$("#brand3").css("display","none");
		} else if( '3' == $(this).val() ) {
			$("#brand1").css("display","none");
			$("#brand2").css("display","none");
			$("#brand3").css("display","");
		} else {
			$("#brand1").css("display","none");
			$("#brand2").css("display","none");
			$("#brand3").css("display","none");
		}
	});

	if( '' != $("select[name='offer[cbrand]'] option:selected").val() ) {
		$("#div_zeus").css("display","");
	}
	if( '1' == $("input[name='offer[howpay]']:checked").val() ) {
		$("#cbrand_zeus").css("display","none");
		$("#div_zeus").css("display","none");
	} else {
		$("#cbrand_zeus").css("display","");
	}
	if( '1' == $("select[name='offer[cbrand]'] option:selected").val() ) {
		$("#brand1").css("display","");
		$("#brand2").css("display","none");
		$("#brand3").css("display","none");
	} else if( '2' == $("select[name='offer[cbrand]'] option:selected").val() ) {
		$("#brand1").css("display","none");
		$("#brand2").css("display","");
		$("#brand3").css("display","none");
	} else if( '3' == $("select[name='offer[cbrand]'] option:selected").val() ) {
		$("#brand1").css("display","none");
		$("#brand2").css("display","none");
		$("#brand3").css("display","");
	} else {
		$("#brand1").css("display","none");
		$("#brand2").css("display","none");
		$("#brand3").css("display","none");
	}
});
