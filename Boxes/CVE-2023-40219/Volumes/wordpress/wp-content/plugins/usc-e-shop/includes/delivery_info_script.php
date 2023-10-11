<?php
/**
 * Delivery and Payment Method Script.
 *
 * @package Welcart
 */

$sendout       = usces_get_send_out_date();
$no_preference = apply_filters( 'usces_filter_label_delivery_date_no_preference', __( 'No preference', 'usces' ) );
$not_a_choice  = apply_filters( 'usces_filter_label_delivery_date_not_a_choice', __( 'There is not a choice.', 'usces' ) );

if ( isset( $this ) ) {
	$usces = &$this;
}

$html = '';

/* Delivery Script */
if ( wel_have_shipped() ) {
	$html = '
<script type="text/javascript">
var toDoubleDigits = function(num) {
	num += "";
	if( num.length === 1 ) num = "0".concat(num);
	return num;
};
var selected_delivery_method = \'\';
var selected_delivery_date = \'\';
var selected_delivery_time = \'\';
var add_shipping = new Array();

function addDate(year, month, day, add) {
	var date = new Date( Number(year), (Number(month)-1), Number(day) );
	var baseSec = date.getTime();
	var addSec = Number(add)*86400000;
	var targetSec = baseSec+addSec;
	date.setTime(targetSec);

	var yy = date.getFullYear()+"";
	var mm = toDoubleDigits(date.getMonth()+1);
	var dd = toDoubleDigits(date.getDate());

	var newdate = new Array();
	newdate["year"] = yy;
	newdate["month"] = mm;
	newdate["day"] = dd;
	return(newdate);
}

jQuery( function($) {';

	/* Selectable delivery method(選択可能な配送方法) */
	$default_deli = array_values( apply_filters( 'usces_filter_get_available_delivery_method', $usces->get_available_delivery_method() ) );

	if ( ! isset( $usces_entries['order']['delivery_method'] ) || '' == $usces_entries['order']['delivery_method'] || ! in_array( $usces_entries['order']['delivery_method'], $default_deli, true ) ) {
		$selected_delivery_method = $default_deli[0];
	} else {
		$selected_delivery_method = $usces_entries['order']['delivery_method'];
	}
	$html .= '
	selected_delivery_method = \'' . $selected_delivery_method . '\';';
	if ( isset( $usces_entries['order']['delivery_date'] ) ) {
		$html .= '
	selected_delivery_date = \'' . $usces_entries['order']['delivery_date'] . '\';';
	}

	/* ItemShipping of goods that are in the cart.(カートに入っている商品の発送日目安) */
	$shipping   = 0;
	$cart       = $usces->cart->get_cart();
	$cart_count = ( $cart && is_array( $cart ) ) ? count( $cart ) : 0;
	for ( $i = 0; $i < $cart_count; $i++ ) {
		$cart_row      = $cart[ $i ];
		$item_shipping = $usces->getItemShipping( $cart_row['post_id'] );
		if ( 0 === (int) $item_shipping || 9 === (int) $item_shipping ) {
			$shipping = 0;
			break;
		}
		if ( $shipping < $item_shipping ) {
			$shipping = $item_shipping;
		}
	}
	$html .= "\n\t" . 'var shipping = ' . (string) $shipping . ';';
	/* 配送業務締時間 */
	$hour  = ( ! empty( $usces->options['delivery_time_limit']['hour'] ) ) ? $usces->options['delivery_time_limit']['hour'] : '00';
	$min   = ( ! empty( $usces->options['delivery_time_limit']['min'] ) ) ? $usces->options['delivery_time_limit']['min'] : '00';
	$html .= "\n\t" . 'var delivery_time_limit_hour = "' . $hour . '";';
	$html .= "\n\t" . 'var delivery_time_limit_min = "' . $min . '";';
	/* 最短宅配時間帯 */
	$html .= "\n\t" . 'var shortest_delivery_time = ' . (int) $usces->options['shortest_delivery_time'] . ';';
	/* 配送希望日を何日後まで表示するか */
	$delivery_after_days = ( ! empty( $usces->options['delivery_after_days'] ) ) ? (int) $usces->options['delivery_after_days'] : 15;
	$html               .= "\n\t" . 'var delivery_after_days = ' . $delivery_after_days . ';';
	/* 配送先県(customer) */
	$html .= "\n\t" . 'var customer_pref = "' . esc_js( $usces_entries['customer']['pref'] ) . '";';
	/* 配送先県(customer/delivery) */
	$delivery_pref    = ( isset( $usces_entries['delivery']['pref'] ) && ! empty( $usces_entries['delivery']['pref'] ) ) ? $usces_entries['delivery']['pref'] : $usces_entries['customer']['pref'];
	$html            .= "\n\t" . 'var delivery_pref = "' . esc_js( $delivery_pref ) . '";';
	$delivery_country = ( isset( $usces_entries['delivery']['country'] ) && ! empty( $usces_entries['delivery']['country'] ) ) ? $usces_entries['delivery']['country'] : $usces_entries['customer']['country'];
	$html            .= "\n\t" . 'var delivery_country = "' . esc_js( $delivery_country ) . '";';
	/* 選択可能な配送方法に設定されている配達日数 */
	$html_days = "\n\t" . 'var delivery_days = [];';
	foreach ( (array) $default_deli as $deli_id ) {
		$index = $usces->get_delivery_method_index( $deli_id );
		if ( 0 <= $index ) {
			$html_days .= "\n\t" . 'delivery_days[' . $deli_id . '] = [];';
			$html_days .= "\n\t" . 'delivery_days[' . $deli_id . '].push("' . $usces->options['delivery_method'][ $index ]['days'] . '");';
		}
	}
	/* 配達日数に設定されている県毎の日数 */
	$target_market = ( isset( $usces->options['system']['target_market'] ) && ! empty( $usces->options['system']['target_market'] ) ) ? $usces->options['system']['target_market'] : usces_get_local_target_market();
	foreach ( (array) $target_market as $tm ) {
		$prefs[ $tm ] = get_usces_states( $tm );
		array_shift( $prefs[ $tm ] );
	}
	$delivery_days       = $usces->options['delivery_days'];
	$delivery_days_count = ( $delivery_days && is_array( $delivery_days ) ) ? count( $delivery_days ) : 0;
	$html_days          .= "\n\t" . 'var delivery_days_value = [];';
	foreach ( (array) $default_deli as $key => $deli_id ) {
		$index = $usces->get_delivery_method_index( $deli_id );
		if ( 0 <= $index ) {
			$days = (int) $usces->options['delivery_method'][ $index ]['days'];
			if ( 0 <= $days ) {
				for ( $i = 0; $i < $delivery_days_count; $i++ ) {
					if ( (int) $delivery_days[ $i ]['id'] == $days ) {
						$html_days .= "\n\t" . 'delivery_days_value[' . $days . '] = [];';
						foreach ( (array) $target_market as $tm ) {
							$html_days .= "\n\t" . 'delivery_days_value[' . $days . ']["' . $tm . '"] = [];';
							foreach ( (array) $prefs[ $tm ] as $pref ) {
								$pref = esc_js( $pref );
								$html_days .= "\n\t" . 'delivery_days_value[' . $days . ']["' . $tm . '"]["' . $pref . '"] = [];';
								if ( isset( $delivery_days[ $i ][ $tm ][ $pref ] ) ) {
									$html_days .= "\n\t" . 'delivery_days_value[' . $days . ']["' . $tm . '"]["' . $pref . '"].push("' . (int) $delivery_days[ $i ][ $tm ][ $pref ] . '");';
								}
							}
						}
					}
				}
			}
		}
	}
	$html                .= apply_filters( 'usces_filter_delivery_days_value', $html_days, $cart, $default_deli, $prefs );
	$business_days        = 0;
	list( $yy, $mm, $dd ) = getToday();
	$business             = ( isset( $usces->options['business_days'][ $yy ][ $mm ][ $dd ] ) ) ? $usces->options['business_days'][ $yy ][ $mm ][ $dd ] : 1;
	while ( 1 !== (int) $business ) {
		$business_days++;
		list( $yy, $mm, $dd ) = getNextDay( $yy, $mm, $dd );
		$business             = $usces->options['business_days'][ $yy ][ $mm ][ $dd ];
	}
	$html .= "\n\t" . 'var business_days = ' . $business_days . ';';

	$html .= "\n\t" . 'selected_delivery_time = \'' . esc_js( $usces_entries['order']['delivery_time'] ) . '\';';

	$html_time = "\n\t" . 'var delivery_time = [];delivery_time[0] = [];';
	foreach ( (array) $usces->options['delivery_method'] as $dmid => $dm ) {
		$dm['time'] = usces_change_line_break( $dm['time'] );
		$lines      = explode( "\n", $dm['time'] );
		$html_time .= "\n\t" . 'delivery_time[' . $dm['id'] . '] = [];';
		foreach ( (array) $lines as $line ) {
			if ( '' !== trim( $line ) ) {
				$html_time .= "\n\t" . 'delivery_time[' . $dm['id'] . '].push("' . trim( $line ) . '");';
			}
		}
	}
	$html .= apply_filters( 'usces_filter_delivery_time_value', $html_time, $cart, $default_deli, $prefs );

	$cart_delivery_script       = "\n
	$(document).on( 'change', '#delivery_method_select', function() {
		orderfunc.make_delivery_date(($('#delivery_method_select option:selected').val()-0));
		orderfunc.make_delivery_time(($('#delivery_method_select option:selected').val()-0));
	});
	$(document).on( 'click', '#delivery_flag1', function() {
		if( customer_pref != delivery_pref ) {
			delivery_pref = customer_pref;
			orderfunc.make_delivery_date(($('#delivery_method_select option:selected').val()-0));
		}
	});
	$(document).on( 'click', '#delivery_flag2', function() {
		if( $('#delivery_flag2').prop('checked') && undefined != $('#delivery_pref').get(0) && 0 < $('#delivery_pref').get(0).selectedIndex ) {
			delivery_pref = $('#delivery_pref').val();
			orderfunc.make_delivery_date(($('#delivery_method_select option:selected').val()-0));
		}
	});
	$(document).on( 'change', '#delivery_pref', function() {
		if( $('#delivery_flag2').prop('checked') && undefined != $('#delivery_pref').get(0) && 0 < $('#delivery_pref').get(0).selectedIndex ) {
			delivery_pref = $('#delivery_pref').val();
			orderfunc.make_delivery_date(($('#delivery_method_select option:selected').val()-0));
		}
	});
	$(document).on( 'blur', '#search_zipcode', function() {
		if( $('#delivery_flag2').prop('checked') && undefined != $('#delivery_pref').get(0) && 0 < $('#delivery_pref').get(0).selectedIndex ) {
			$('#search_zipcode_change').val($('#zipcode').val());
			delivery_pref = $('#delivery_pref').val();
			orderfunc.make_delivery_date(($('#delivery_method_select option:selected').val()-0));
		}
	});

	orderfunc = {
		make_delivery_date : function(selected) {
			var option = '';
			var message = '';
			if( delivery_days[selected] != undefined && 0 <= delivery_days[selected] ) {
				switch(shipping) {
				case 0:
				case 9:
					break;
				default:
					var date = new Array();
					date['year'] = '" . $sendout['sendout_date']['year'] . "';
					date['month'] = '" . $sendout['sendout_date']['month'] . "';
					date['day'] = '" . $sendout['sendout_date']['day'] . "';
					if( delivery_days_value[delivery_days[selected]] != undefined ) {
						if( delivery_days_value[delivery_days[selected]][delivery_country][delivery_pref] != undefined ) {
							date = addDate(date[\"year\"], date[\"month\"], date[\"day\"], delivery_days_value[delivery_days[selected]][delivery_country][delivery_pref] );
						}
					}
					var date_str = date[\"year\"]+\"-\"+date[\"month\"]+\"-\"+date[\"day\"];
					switch(shortest_delivery_time) {
					case 0:
						//message = '最短 '+date_str+' からご指定いただけます。';
						break;
					case 1:
						message = '最短 '+date_str+' の午前中からご指定いただけます。';
						break;
					case 2:
						message = '最短 '+date_str+' の午後からご指定いただけます。';
						break;
					}";
	$delivery_after_days_script = "
					option += '<option value=\"" . $no_preference . "\">" . $no_preference . "</option>';
					for( var i = 0; i < delivery_after_days; i++ ) {
						date_str = date[\"year\"]+\"-\"+date[\"month\"]+\"-\"+date[\"day\"];
						if( date_str == selected_delivery_date ) {
							option += '<option value=\"'+date_str+'\" selected>'+date_str+'</option>';
						} else {
							option += '<option value=\"'+date_str+'\">'+date_str+'</option>';
						}
						date = addDate( date[\"year\"], date[\"month\"], date[\"day\"], 1 );
					}";
	$cart_delivery_script      .= apply_filters( 'usces_delivery_after_days_script', $delivery_after_days_script );
	$cart_delivery_script      .= "
					break;
				}
			}
			if( option == '' ) {
				option = '<option value=\"" . $not_a_choice . "\">'+'" . $not_a_choice . "'+'</option>';
			}
			$(\"#delivery_date_select\").html(option);
			$(\"#delivery_time_limit_message\").html(message);
		},

		make_delivery_time : function(selected) {
			var option = '';
			if( delivery_time[selected] != undefined ) {
				for( var i = 0; i < delivery_time[selected].length; i++ ) {
					if( delivery_time[selected][i] == selected_delivery_time ) {
						option += '<option value=\"'+delivery_time[selected][i]+'\" selected=\"selected\">'+delivery_time[selected][i]+'</option>';
					} else {
						option += '<option value=\"'+delivery_time[selected][i]+'\">'+delivery_time[selected][i]+'</option>';
					}
				}
			}
			if( option == '' ) {
				option = '<option value=\"" . __( 'There is not a choice.', 'usces' ) . "\">'+'" . __( 'There is not a choice.', 'usces' ) . "'+'</option>';
			}
			$(\"#delivery_time_select\").html( option );
		}
	};";

	if ( 1 !== (int) $usces_entries['delivery']['delivery_flag'] ) {
		$cart_delivery_script .= "\n\t$(\"#delivery_table\").css({\"display\":\"none\"});";
	}
	$cart_delivery_script .= "\n\t" . 'orderfunc.make_delivery_date(selected_delivery_method);';
	$cart_delivery_script .= "\n\t" . 'orderfunc.make_delivery_time(selected_delivery_method);';
	$html                 .= apply_filters( 'usces_filter_cart_delivery_script', $cart_delivery_script, $usces_entries, $sendout );
}

/* Payment Method Script */
if ( empty( $html ) ) {
	$html = '
<script type="text/javascript">
jQuery( function($) {';
}
$payments_str = '';
$payments_arr = array();
$payments     = usces_get_system_option( 'usces_payment_method', 'sort' );
$payments     = apply_filters( 'usces_filter_available_payment_method', $payments );
foreach ( (array) $payments as $array ) {
	switch ( $array['settlement'] ) {
		case 'acting_remise_card':
			$paymod_base = 'remise';
			if ( 'on' == $usces->options['acting_settings'][ $paymod_base ]['card_activate'] &&
				'on' == $usces->options['acting_settings'][ $paymod_base ]['howpay'] &&
				'on' == $usces->options['acting_settings'][ $paymod_base ]['activate'] ) {
				$payments_str  .= "'" . $array['name'] . "': '" . $paymod_base . "', ";
				$payments_arr[] = $paymod_base;
			}
			break;
	}
	$payments_str = apply_filters( 'usces_filter_payments_str', $payments_str, $array );
	$payments_arr = apply_filters( 'usces_filter_payments_arr', $payments_arr, $array );
}
$payments_str = rtrim( $payments_str, ', ' );
$html        .= "\n\tvar uscesPaymod = { " . $payments_str . " };
	$(document).on( 'click', \"input[name='offer\\[payment_name\\]']\", function() {";
foreach ( $payments_arr as $pm ) {
	$html .= "\n\t\t$(\"#" . $pm . "\").css({\"display\":\"none\"});\n";
}
$html .= "\n\t\tvar chk_pay = $(\"input[name='offer\\[payment_name\\]']:checked\").val();
		if( uscesPaymod[chk_pay] != '' ) {
			$(\"#\"+uscesPaymod[chk_pay]).css({\"display\":\"table\"});
		}
	});";
foreach ( $payments_arr as $pn => $pm ) {
	$html  .= "\n\t$(\"#" . $pm . "\").css({\"display\":\"none\"});";
	$howpay = '';
	$html  .= apply_filters( 'usces_filter_howpay', $howpay, $pm, $payments_arr );
}
$html .= "\n\tch_pay = $(\"input[name='offer\\[payment_name\\]']:checked\").val();
	if( uscesPaymod[ch_pay] != '' ) {
		$(\"#\"+uscesPaymod[ch_pay]).css({\"display\":\"\"});
	}";
$html .= "\n});
</script>\n";
