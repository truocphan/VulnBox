jQuery( document ).ready( function( $ ) {

	$( document ).on( "click", ".card-update", function( e ) {
		var update_mode = $( this ).attr( "data-update_mode" );
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

		$( "input[name='update']" ).val( update_mode );
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
	});

	$( document ).on( "click", "#card-delete", function( e ) {
		if ( ! confirm( paygent_params.message.confirm_deletion ) ) {
			return;
		}
		$( "input[name='update']" ).val( 'delete' );
		$( "#member-card-info" ).submit();
	});
});

function execPurchase( response ) {
	if ( response.result == "0000" ) {
		document.getElementById( "token" ).value = response.tokenizedCardObject.token;
		document.getElementById( "masked_card_number" ).value = response.tokenizedCardObject.masked_card_number;
		document.getElementById( "valid_until" ).value = response.tokenizedCardObject.valid_until;
		document.getElementById( "fingerprint" ).value = response.tokenizedCardObject.fingerprint;
		document.getElementById( "hc" ).value = response.hc;
		document.getElementById( "member-card-info" ).submit();
	} else {
		alert( paygent_params.message.error_token );
		return false;
	}
}
