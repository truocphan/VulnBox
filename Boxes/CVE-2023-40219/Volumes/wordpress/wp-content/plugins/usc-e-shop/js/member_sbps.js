jQuery( document ).ready( function( $ ) {

	$( document ).on( "click", ".card-update", function( e ) {
		var update_mode = $( this ).attr( "data-update_mode" );
		var check = true;
		if ( "" == $( "#cc_number" ).val() ) {
			check = false;
		}
		if ( undefined == $( "#cc_expyy" ).get( 0 ) || undefined == $( "#cc_expmm" ).get( 0 ) ) {
			check = false;
		} else if ( "" == $( "#cc_expyy option:selected" ).val() || "" == $( "#cc_expmm option:selected" ).val() ) {
			check = false;
		} else if ( "----" == $( "#cc_expyy option:selected" ).val() || "--" == $( "#cc_expmm option:selected" ).val() ) {
			check = false;
		}
		if ( "" == $( "#cc_seccd" ).val() ) {
			check = false;
		}
		if ( ! check ) {
			alert( sbps_params.message.error_token );
			return false;
		}

		$( "input[name='update']" ).val( update_mode );
		var cc_expyy = $( "#cc_expyy option:selected" ).val();
		var cc_expmm = $( "#cc_expmm option:selected" ).val();

		com_sbps_system.generateToken({
			merchantId : sbps_params.sbps_merchantId,
			serviceId : sbps_params.sbps_serviceId,
			ccNumber : $( "#cc_number" ).val(),
			ccExpiration : cc_expyy.toString() + cc_expmm.toString(),
			securityCode : $( "#cc_seccd" ).val()
		}, afterGenerateToken );
		return false;
	});

	$( document ).on( "click", "#card-delete", function( e ) {
		if ( ! confirm( sbps_params.message.confirm_deletion ) ) {
			return;
		}
		$( "input[name='update']" ).val( 'delete' );
		$( "#member-card-info" ).submit();
	});
});

var afterGenerateToken = function( response ) {
	console.log( response );
	if ( response.result == "OK" ) {
		document.getElementById( "token" ).value = response.tokenResponse.token;
		document.getElementById( "tokenKey" ).value = response.tokenResponse.tokenKey;
		document.getElementById( "member-card-info" ).submit();
	} else {
		console.log( response.errorCode );
		var message = sbps_params.message.error_token;
		if ( 5 == response.errorCode.length ) {
			var error_type = response.errorCode.substr( 0, 2 );
			var error_field = response.errorCode.substr( 2, 3 );
			if ( '99' != error_type ) {
				if ( '003' == error_field ) {
					message = sbps_params.message.error_card_number;
				} else if ( '004' == error_field ) {
					message = sbps_params.message.error_card_expym;
				} else if ( '005' == error_field ) {
					message = sbps_params.message.error_card_seccd;
				}
			}
		}
		alert( message );
		return false;
	}
}
