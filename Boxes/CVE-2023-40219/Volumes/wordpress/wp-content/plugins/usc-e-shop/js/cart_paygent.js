jQuery( document ).ready( function( $ ) {

	$( 'body input[type="submit"]' ).each( function( i, elem ) {
		if ( "confirm" == $( this ).attr( "name" ) ) {
			$( this ).parents( "form" ).attr( "id", "delivery-form" );
		}
	});

	$( document ).on( "click", 'body input[type="submit"]', function( e ) {
		if ( "module" == paygent_params.card_service_type && "confirm" == $( this ).attr( "name" ) && $( "#paygent_card_form" ).css( "display" ) != "none" ) {
			var stock_card = ( 0 < $( "input[name=stock_card]" ).length ) ? $( "input[name=stock_card]:checked" ).val() : "new";
			if ( "new" == stock_card ) {
				var message = "";
				if ( "" == $( "#card_number" ).val() ) {
					message += paygent_params.message.error_card_number + "\n";
				}
				if ( undefined == $( "#expire_year" ).get( 0 ) || undefined == $( "#expire_month" ).get( 0 ) ) {
					message += paygent_params.message.error_card_expire + "\n";
				} else if ( "" == $( "#expire_year option:selected" ).val() || "" == $( "#expire_month option:selected" ).val() ) {
					message += paygent_params.message.error_card_expire + "\n";
				}
				if ( "on" == paygent_params.use_card_conf_number ) {
					if ( "" == $( "#cvc" ).val() ) {
						message += paygent_params.message.error_card_cvc + "\n";
					}
				}
				if ( "" != message ) {
					alert( message );
					return false;
				}

				var card_number  = $( "#card_number" ).val();
				var expire_year  = $( "#expire_year option:selected" ).val();
				var expire_month = $( "#expire_month option:selected" ).val();
				var cvc          = ( "on" == paygent_params.use_card_conf_number ) ? $( "#cvc" ).val() : "";

				var paygentToken = new PaygentToken();
				paygentToken.createToken(
					paygent_params.seq_merchant_id,
					paygent_params.token_key,
					{
						card_number: card_number,
						expire_year: expire_year,
						expire_month: expire_month,
						cvc: cvc,
						name: ""
					}, execPurchase
				);
				return false;
			} else if ( "stock" == stock_card && "on" == paygent_params.use_card_conf_number ) {
				var message = "";
				if ( "" == $( "#cvc" ).val() ) {
					message += paygent_params.message.error_card_cvc + "\n";
				}
				if ( "" != message ) {
					alert( message );
					return false;
				}

				var cvc = $( "#cvc" ).val();

				var paygentToken = new PaygentToken();
				paygentToken.createCvcToken(
					paygent_params.seq_merchant_id,
					paygent_params.token_key,
					{
						cvc: cvc
					}, execPurchase
				);
				return false;
			} else {
				$( "delivery-form" ).submit();
			}

		} else if ( "module" == paygent_params.conv_service_type && "confirm" == $( this ).attr( "name" ) && $( "#paygent_conv_form" ).css( "display" ) != "none" ) {
			var message = '';
			if ( "" == $( "#customer_family_name" ).val() ) {
				message += paygent_params.message.error_customer_family_name + "\n";
			}
			if ( "" == $( "#customer_name" ).val() ) {
				message += paygent_params.message.error_customer_name + "\n";
			}
			if ( "" == $( "#customer_tel" ).val() ) {
				message += paygent_params.message.error_customer_tel + "\n";
			}
			if ( "" != message ) {
				alert( message );
				return false;
			}
			$( "delivery-form" ).submit();

		} else {
			$( "delivery-form" ).submit();
		}
	});

	if ( "module" == paygent_params.card_service_type && $( "input[name=stock_card]" ).length ) {
		$( document ).on( "click", ".stock_card", function( e ) {
			if ( "stock" == $( "input[name=stock_card]:checked" ).val() ) {
				$( ".paygent_new_card_area" ).hide();
				$( "#card_number" ).prop( "disabled", true );
				if( $( "#cust_manage" ).length ) {
					$( "#cust_manage" ).prop( "disabled", true );
					$( "#cust_manage_label" ).css( "color", "#848484" );
				}
				$( "#expire_month" ).prop( "disabled", true ).css( "background-color", "#ebebe4" );
				$( "#expire_year" ).prop( "disabled", true ).css( "background-color", "#ebebe4" );
			} else {
				$( ".paygent_new_card_area" ).show();
				$( "#card_number" ).prop( "disabled", false );
				if ( $( "#cust_manage" ).length ) {
					$( "#cust_manage" ).prop( "disabled", false );
					$( "#cust_manage_label" ).css( "color", "#000" );
				}
				$( "#expire_month" ).prop( "disabled", false ).css( "background-color", "#fff" );
				$( "#expire_year" ).prop( "disabled", false ).css( "background-color", "#fff" );
			}
		});
		$( "#stock_card_use" ).prop( "checked", true ).trigger( "click" );
		if ( $( "#split_type" ).length ) {
			/* VISA|Master Card|JCB */
			if ( "4535" == $( "#split_type" ).val() ) {
				$( "#split_count_default" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_4535" ).prop( "disabled", false ).css( "display", "inline" );
				$( "#split_count_37" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_36" ).prop( "disabled", true ).css( "display", "none" );
			/* Diners Club */
			} else if ( "36" == $( "#split_type" ).val() ) {
				$( "#split_count_default" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_4535" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_37" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_36" ).prop( "disabled", false ).css( "display", "inline" );
			/* American Express */
			} else if ( "37" == $( "#split_type" ).val() ) {
				$( "#split_count_default" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_4535" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_37" ).prop( "disabled", false ).css( "display", "inline" );
				$( "#split_count_36" ).prop( "disabled", true ).css( "display", "none" );
			} else {
				$( "#split_count_default" ).prop( "disabled", false ).css( "display", "inline" );
				$( "#split_count_4535" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_37" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_36" ).prop( "disabled", true ).css( "display", "none" );
			}
		}
	}

	if ( "module" == paygent_params.card_service_type ) {
		$( document ).on( "change", "#card_number", function( e ) {
			var first_c  = $( this ).val().substr( 0, 1 );
			var second_c = $( this ).val().substr( 1, 1 );
			/* VISA|Master Card|JCB */
			if ( '4' == first_c || '5' == first_c || ( '3' == first_c && '5' == second_c ) ) {
				$( "#split_count_default" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_4535" ).prop( "disabled", false ).css( "display", "inline" );
				$( "#split_count_37" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_36" ).prop( "disabled", true ).css( "display", "none" );
			/* Diners Club */
			} else if ( '3' == first_c && '6' == second_c ) {
				$( "#split_count_default" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_4535" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_37" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_36" ).prop( "disabled", false ).css( "display", "inline" );
			/* American Express */
			} else if ( '3' == first_c && '7' == second_c ) {
				$( "#split_count_default" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_4535" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_37" ).prop( "disabled", false ).css( "display", "inline" );
				$( "#split_count_36" ).prop( "disabled", true ).css( "display", "none" );
			} else {
				$( "#split_count_default" ).prop( "disabled", false ).css( "display", "inline" );
				$( "#split_count_4535" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_37" ).prop( "disabled", true ).css( "display", "none" );
				$( "#split_count_36" ).prop( "disabled", true ).css( "display", "none" );
			}
		});
	}
});

function execPurchase( response ) {
	if ( response.result == "0000" ) {
		document.getElementById( "token" ).value = response.tokenizedCardObject.token;
		document.getElementById( "masked_card_number" ).value = response.tokenizedCardObject.masked_card_number;
		document.getElementById( "valid_until" ).value = response.tokenizedCardObject.valid_until;
		document.getElementById( "fingerprint" ).value = response.tokenizedCardObject.fingerprint;
		document.getElementById( "hc" ).value = response.hc;
		document.getElementById( "delivery-form" ).submit();
	} else {
		alert( paygent_params.message.error_token );
		return false;
	}
}
