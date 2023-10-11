jQuery( document ).ready( function( $ ) {

	$( 'body input[type="submit"]' ).each( function( i, elem ) {
		if( "confirm" == $( this ).attr( "name" ) ) {
			$( this ).parents( "form" ).attr( "id", "delivery-form" );
		}
	});

	$( document ).on( "click", 'body input[type="submit"]', function( e ) {
		if( "confirm" == $( this ).attr( "name" ) && $( "#sbps_form" ).css( "display" ) != "none" ) {
			if( $( "input[name=cust_quick]" ).val() == undefined || "new" == $( "input[name=cust_quick]:checked" ).val() ) {

				var check = true;
				if( "" == $( "#cc_number" ).val() ) {
					check = false;
				}
				if( undefined == $( "#cc_expyy" ).get( 0 ) || undefined == $( "#cc_expmm" ).get( 0 ) ) {
					check = false;
				} else if( "" == $( "#cc_expyy option:selected" ).val() || "" == $( "#cc_expmm option:selected" ).val() ) {
					check = false;
				} else if( "----" == $( "#cc_expyy option:selected" ).val() || "--" == $( "#cc_expmm option:selected" ).val() ) {
					check = false;
				}
				if( "" == $( "#cc_seccd" ).val() ) {
					check = false;
				}
				if( !check ) {
					alert( sbps_params.message.error_token );
					return false;
				}

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

			} else {
				$( "delivery-form" ).submit();
			}
		} else {
			$( "delivery-form" ).submit();
		}
	});

	if( $( "input[name=cust_quick]" ).val() != undefined ) {
		$( document ).on( "click", ".cust_quick", function( e ) {
			if( "quick" == $( "input[name=cust_quick]:checked" ).val() ) {
				$( "#cc_number" ).prop( "disabled", true );
				if( $( "#cust_manage" ).val() != undefined ) {
					$( "#cust_manage" ).prop( "disabled", true );
					$( "#cust_manage_label" ).css( "color", "#848484" );
				}
				$( "#cc_expmm" ).prop( "disabled", true ).css( "background-color", "#ebebe4" );
				$( "#cc_expyy" ).prop( "disabled", true ).css( "background-color", "#ebebe4" );
				$( "#cc_seccd" ).prop( "disabled", true );
			} else {
				$( "#cc_number" ).prop( "disabled", false );
				if( $( "#cust_manage" ).val() != undefined ) {
					$( "#cust_manage" ).prop( "disabled", false );
					$( "#cust_manage_label" ).css( "color", "#000" );
				}
				$( "#cc_expmm" ).prop( "disabled", false ).css( "background-color", "#fff" );
				$( "#cc_expyy" ).prop( "disabled", false ).css( "background-color", "#fff" );
				$( "#cc_seccd" ).prop( "disabled", false );
			}
		});
		$( "#cust_quick_use" ).prop( "checked", true ).trigger( "click" );
	}
});

var afterGenerateToken = function( response ) {
	//console.log( response );
	if( response.result == "OK" ) {
		document.getElementById( "token" ).value = response.tokenResponse.token;
		document.getElementById( "tokenKey" ).value = response.tokenResponse.tokenKey;
		document.getElementById( "delivery-form" ).submit();
	} else {
		//console.log( response.errorCode );
		var message = sbps_params.message.error_token;
		if( 5 == response.errorCode.length ) {
			var error_type = response.errorCode.substr( 0, 2 );
			var error_field = response.errorCode.substr( 2, 3 );
			if( '99' != error_type ) {
				if( '003' == error_field ) {
					message = sbps_params.message.error_card_number;
				} else if( '004' == error_field ) {
					message = sbps_params.message.error_card_expym;
				} else if( '005' == error_field ) {
					message = sbps_params.message.error_card_seccd;
				}
			}
		}
		alert( message );
		return false;
	}
}
