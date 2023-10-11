<?php
/**
 * Welcart System Options
 *
 * Functions for system option operations.
 *
 * @package Welcart
 */

defined( 'ABSPATH' ) || exit;

/**
 * Add system option
 *
 * @param string $option_name Option name.
 * @param mixed  $newvalue New value.
 * @return int
 */
function usces_add_system_option( $option_name, $newvalue ) {
	global $usces;

	$newvalue     = $usces->stripslashes_deep_post( $newvalue );
	$option_value = get_option( $option_name );

	if ( ! empty( $option_value ) && is_array( $option_value ) ) {
		$option_num = count( $option_value );
		$unique     = true;
		$sortnull   = true;
		$sort       = array();
		foreach ( (array) $option_value as $value ) {
			if ( $value['name'] == $newvalue['name'] ) {
				$unique = false;
			}
			if ( ! isset( $value['sort'] ) ) {
				$sortnull = false;
			}
			$sort[] = $value['sort'];
		}
		if ( ! $unique ) {
			return -1;
		}
		rsort( $sort );
		$next_number       = reset( $sort ) + 1;
		$unique_sort       = array_unique( $sort );
		$unique_sort_count = count( $unique_sort );
		if ( $option_num !== $unique_sort_count || $option_num !== $next_number || ! $sortnull ) {
			// To repair the sort data.
			$i = 0;
			foreach ( $option_value as $opkey => $opvalue ) {
				$option_value[ $opkey ]['sort'] = $i;
				$i++;
			}
		}
	}
	$newvalue['sort'] = ! empty( $option_num ) ? $option_num : 0;
	$option_value[]   = $newvalue;
	update_option( $option_name, $option_value );
	krsort( $option_value );
	reset( $option_value );
	$last_index = key( $option_value );

	return $last_index;
}

/**
 * Update system option
 *
 * @param string $option_name Option name.
 * @param int    $index Index.
 * @param mixed  $newvalue New value.
 * @return int
 */
function usces_update_system_option( $option_name, $index, $newvalue ) {
	global $usces;

	$newvalue     = $usces->stripslashes_deep_post( $newvalue );
	$option_value = get_option( $option_name );

	if ( ! empty( $option_value ) ) {
		$unique = true;
		foreach ( (array) $option_value as $value_id => $value ) {
			if ( $value['name'] == $newvalue['name'] && $value_id != $index ) {
				$unique = false;
				break;
			}
		}
		if ( ! $unique ) {
			return -1;
		}
		$option_value[ $index ] = $newvalue;
		update_option( $option_name, $option_value );
		$lid = $index;
	} else {
		$lid = -1;
	}

	return $lid;
}

/**
 * Get system option
 *
 * @param string $option_name Option name.
 * @param string $keyflag Key.
 * @return array
 */
function usces_get_system_option( $option_name, $keyflag = 'sort' ) {
	$sysopts      = array();
	$option_value = get_option( $option_name );

	if ( ! is_array( $option_value ) ) {
		return $sysopts;
	}

	foreach ( $option_value as $id => $value ) {
		$key = isset( $value[ $keyflag ] ) ? $value[ $keyflag ] : $value['sort'];
		switch ( $option_name ) {
			case 'usces_payment_method':
				$sysopts[ $key ] = array(
					'id'          => $id,
					'name'        => esc_js( $value['name'] ),
					'explanation' => wel_esc_script( $value['explanation'] ),
					'settlement'  => $value['settlement'],
					'module'      => $value['module'],
					'sort'        => $value['sort'],
					'use'         => isset( $value['use'] ) ? $value['use'] : 'activate',
				);
				break;
		}
	}
	ksort( $sysopts );

	return $sysopts;
}

/**
 * Sort system option
 *
 * @param string $option_name Option name.
 * @param string $idstr Ids.
 * @return void
 */
function usces_sort_system_option( $option_name, $idstr ) {
	$option_value = get_option( $option_name );

	if ( ! empty( $option_value ) ) {
		$ids = explode( ',', $idstr );
		$c   = 0;
		foreach ( (array) $ids as $id ) {
			$option_value[ $id ]['sort'] = $c;
			$c++;
		}
		update_option( $option_name, $option_value );
	}
	return;
}

/**
 * Delete system option
 *
 * @param string $option_name Option name.
 * @param string $id Id.
 * @return void
 */
function usces_del_system_option( $option_name, $id ) {
	$option_value = get_option( $option_name );

	if ( ! empty( $option_value ) && isset( $option_value[ $id ] ) && ! empty( $option_value ) ) {
		unset( $option_value[ $id ] );
		$op = array();
		foreach ( (array) $option_value as $opkey => $opvalue ) {
			$op[ $opvalue['sort'] ] = $opkey;
		}
		ksort( $op );
		$c = 0;
		foreach ( $op as $opid ) {
			$option_value[ $opid ]['sort'] = $c;
			$c++;
		}
		update_option( $option_name, $option_value );
	}
	return;
}

/**
 * Payment list
 *
 * @param array $option_value Payment values.
 */
function payment_list( $option_value ) {
	// Exit if no meta.
	if ( empty( $option_value ) ) :
		?>
		<table id="payment-table" class="list" style="display: none;">
			<thead>
			<tr>
				<th class="hanldh">&emsp;</th>
				<th class="left"><?php esc_html_e( 'A payment method name', 'usces' ); ?></th>
				<th><?php esc_html_e( 'explanation', 'usces' ); ?></th>
				<th><?php esc_html_e( 'Type of payment', 'usces' ); ?></th>
				<th><?php esc_html_e( 'Payment module', 'usces' ); ?></th>
			</tr>
			</thead>
			<tbody id="payment-list">
			<tr><td></td><td></td><td></td></tr>
			</tbody>
		</table>
		<?php
	else :
		?>
		<table id="payment-table" class="list">
			<thead>
			<tr>
				<th class="hanldh">&emsp;</th>
				<th class="paymentname"><?php esc_html_e( 'A payment method name', 'usces' ); ?></th>
				<th class="paymentexplanation"><?php esc_html_e( 'explanation', 'usces' ); ?></th>
				<th class="paymentsettlement"><?php esc_html_e( 'Type of payment', 'usces' ); ?></th>
				<th class="paymentmodule"><?php esc_html_e( 'Payment module', 'usces' ); ?></th>
			</tr>
			</thead>
			<tbody id="payment-list">
			<?php
			foreach ( $option_value as $value ) {
				echo _payment_list_row( $value );
			}
			?>
			</tbody>
		</table>
		<div id="payment_ajax-response"></div>
		<?php
	endif;
}

/**
 * Payment list row
 *
 * @param array $value Payment value.
 */
function _payment_list_row( $value ) {

	if ( empty( $value ) ) {
		return;
	}

	$r           = '';
	$style       = '';
	$id          = (int) $value['id'];
	$name        = $value['name'];
	$explanation = ( ! empty( $value['explanation'] ) ) ? $value['explanation'] : '';
	$settlement  = $value['settlement'];
	$module      = ( ! empty( $value['module'] ) ) ? $value['module'] : '';
	$sort        = (int) $value['sort'];
	$use         = ( isset( $value['use'] ) ) ? $value['use'] : 'activate';

	$payment_type = wel_get_payment_type();

	ob_start();
	?>
	<tr class="metastuffrow">
	<td colspan="5">
		<table id="payment-<?php echo esc_attr( $id ); ?>" class="metastufftable">
			<tr>
				<th class="handlb" rowspan="2">&emsp;</th>
				<td class="paymentname">
					<div><input name="payment[<?php echo esc_attr( $id ); ?>][name]" id="payment[<?php echo esc_attr( $id ); ?>][name]" class="metaboxfield" type="text" value="<?php echo esc_attr( $name ); ?>" /></div>
					<div><input name="payment[<?php echo esc_attr( $id ); ?>][use]" id="payment[<?php echo esc_attr( $id ); ?>][use_activate]" type="radio" value="activate"<?php checked( 'deactivate' !== $use, true ); ?> /><label for="payment[<?php echo esc_attr( $id ); ?>][use_activate]"><?php esc_html_e( 'Activate', 'usces' ); ?></label>&emsp;
					<input name="payment[<?php echo esc_attr( $id ); ?>][use]" id="payment[<?php echo esc_attr( $id ); ?>][use_deactivate]" type="radio" value="deactivate"<?php checked( 'deactivate' === $use, true ); ?> /><label for="payment[<?php echo esc_attr( $id ); ?>][use_deactivate]"><?php esc_html_e( 'Deactivate', 'usces' ); ?></label></div>
				</td>
				<td class="paymentexplanation"><textarea name="payment[<?php echo esc_attr( $id ); ?>][explanation]" id="payment[<?php echo esc_attr( $id ); ?>][explanation]" class="metaboxfield"><?php echo esc_attr( $explanation ); ?></textarea></td>
				<td class="paymentsettlement">
					<select name="payment[<?php echo esc_attr( $id ); ?>][settlement]" id="payment[<?php echo esc_attr( $id ); ?>][settlement]" class="metaboxfield">
						<option value="#NONE#"><?php esc_html_e( '-- Select --', 'usces' ); ?></option>
					<?php
					foreach ( $payment_type as $psk => $psv ) :
						?>
						<option value="<?php echo esc_attr( $psk ); ?>"<?php selected( $settlement, $psk ); ?>><?php echo esc_html( $psv ); ?></option>
						<?php
					endforeach;
					?>
					</select>
				</td>
				<td class="paymentmodule"><div><input name="payment[<?php echo esc_attr( $id ); ?>][module]" id="payment[<?php echo esc_attr( $id ); ?>][module]" class="metaboxfield" type="text" value="<?php echo esc_attr( $module ); ?>" /></div></td>
			</tr>
			<tr>
				<td colspan="4" class="submittd">
					<div id="paymentsubmit-<?php echo esc_attr( $id ); ?>" class="submit">
						<input name="deletepayment" id="deletepayment[<?php echo esc_attr( $id ); ?>]" type="button" class="button" value="<?php esc_attr_e( 'Delete' ); ?>" onclick="payment.post('del', <?php echo esc_attr( $id ); ?>);" />
						<input name="updatepayment" id="updatepayment[<?php echo esc_attr( $id ); ?>]" type="button" class="button" value="<?php esc_attr_e( 'Update' ); ?>" onclick="payment.post('update', <?php echo esc_attr( $id ); ?>);" />
						<input name="payment[<?php echo esc_attr( $id ); ?>][sort]" id="payment[<?php echo esc_attr( $id ); ?>][sort]" type="hidden" value="<?php echo esc_attr( $sort ); ?>" />
					</div>
					<div id="payment_loading-<?php echo esc_attr( $id ); ?>" class="meta_submit_loading"></div>
				</td>
			</tr>
		</table>
	</td>
	</tr>
	<?php
	$r = ob_get_contents();
	ob_end_clean();
	return $r;
}

/**
 * Add payment form
 */
function payment_form() {
	$payment_type = wel_get_payment_type();
	?>
	<p><strong><?php esc_html_e( 'Add a new method forpayment ', 'usces' ); ?> : </strong></p>
	<table id="newmeta2">
		<thead>
		<tr>
			<th class="left"><?php esc_html_e( 'A payment method name', 'usces' ); ?></th>
			<th><?php esc_html_e( 'explanation', 'usces' ); ?></th>
			<th><?php esc_html_e( 'Type of payment', 'usces' ); ?></th>
			<th><?php esc_html_e( 'Payment module', 'usces' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td class="paymentname"><input type="text" id="newname" name="newname" class="metaboxfield" tabindex="7" value="" /></td>
			<td class="paymentexplanation"><textarea id="newexplanation" name="newexplanation" class="metaboxfield"></textarea></td>
			<td class="paymentsettlement">
				<select name="newsettlement" id="newsettlement" class="metaboxfield">
					<option value="#NONE#"><?php esc_html_e( '-- Select --', 'usces' ); ?></option>
			<?php foreach ( $payment_type as $psk => $psv ) : ?>
					<option value="<?php echo esc_attr( $psk ); ?>"><?php echo esc_html( $psv ); ?></option>
			<?php endforeach; ?>
				</select>
			</td>
			<td class="paymentmodule"><input type="text" id="newmodule" name="newmodule" class="metaboxfield" tabindex="9" value="" /></td>
		</tr>
		<tr>
			<td colspan="4" class="submittd">
			<div id="newpaymentsubmit" class="submit"><input name="add_payment" type="button" class="button" id="add_payment" tabindex="9" value="<?php esc_attr_e( 'Add a new method forpayment ', 'usces' ); ?>" onclick="payment.post('add', 0);" /></div>
			<div id="newpayment_loading" class="meta_submit_loading"></div>
			</td>
		</tr>
		</tbody>
	</table>
	<?php
}

/**
 * Payment Type
 *
 * @return array
 */
function wel_get_payment_type() {
	global $usces, $payment_structure;

	$payment_type = $usces->payment_structure;
	foreach ( $payment_structure as $key => $value ) {
		if ( ! array_key_exists( $key, $payment_type ) ) {
			$payment_type[ $key ] = $value;
		}
	}
	return $payment_type;
}

/**
 * Payment Method Ajax
 */
function payment_ajax() {

	check_admin_referer( 'admin_setup', 'wc_nonce' );
	if ( 4 > usces_get_admin_user_level() ) {
		die( 'user_level' );
	}

	$id = null;

	if ( 'payment_ajax' !== wp_unslash( $_POST['action'] ) ) {
		die(0);
	}

	if ( isset( $_POST['update'] ) ) {
		$id = up_payment_method();
	} elseif ( isset( $_POST['delete'] ) ) {
		$id = del_payment_method();
	} elseif ( isset( $_POST['sort'] ) ) {
		sort_payment_method();
	} else {
		$id = add_payment_method();
	}

	$option_value = usces_get_system_option( 'usces_payment_method', 'sort' );
	$r            = '';
	foreach ( $option_value as $value ) {
		$r .= _payment_list_row( $value );
	}
	$response = array(
		'meta_id'  => $id,
		'meta_row' => $r,
	);

	wp_send_json( $response );
}

/**
 * Add payment
 *
 * @return mixed
 */
function add_payment_method() {
	$newvalue['name']        = isset( $_POST['newname'] ) ? trim( wp_unslash( $_POST['newname'] ) ) : '';
	$newvalue['explanation'] = isset( $_POST['newexplanation'] ) ? trim( wp_unslash( $_POST['newexplanation'] ) ) : '';
	$newvalue['settlement']  = isset( $_POST['newsettlement'] ) ? wp_unslash( $_POST['newsettlement'] ) : '';
	$newvalue['module']      = isset( $_POST['newmodule'] ) ? trim( wp_unslash( $_POST['newmodule'] ) ) : '';
	$newvalue                = apply_filters( 'usces_filter_add_payment_method', $newvalue );
	// if ( 'acting_paypal_ec' == $newvalue['settlement'] ) {
	// 	$newvalue = usces_add_payment_method_paypal_explanation( $newvalue );
	// }
	if ( ! empty( $newvalue['name'] ) ) {
		$lid = usces_add_system_option( 'usces_payment_method', $newvalue );
		// if ( USCES_JP && 0 <= $lid && 'acting_paypal_ec' == $newvalue['settlement'] ) {
		// 	$lid = usces_add_payment_method_paypal_bank( $newvalue );
		// }
		return $lid;
	}
	return false;
}

/**
 * Update payment
 *
 * @return mixed
 */
function up_payment_method() {
	$value                = array();
	$id                   = wp_unslash( $_POST['id'] );
	$value['name']        = isset( $_POST['name'] ) ? trim( wp_unslash( $_POST['name'] ) ) : '';
	$value['explanation'] = isset( $_POST['explanation'] ) ? trim( wp_unslash( $_POST['explanation'] ) ) : '';
	$value['settlement']  = isset( $_POST['settlement'] ) ? wp_unslash( $_POST['settlement'] ) : '';
	$value['module']      = isset( $_POST['module'] ) ? trim( wp_unslash( $_POST['module'] ) ) : '';
	$value['sort']        = isset( $_POST['sort'] ) ? (int) wp_unslash( $_POST['sort'] ) : 0;
	$value['use']         = isset( $_POST['use'] ) ? wp_unslash( $_POST['use'] ) : 'activate';
	$value                = apply_filters( 'usces_filter_up_payment_method', $value );

	if ( ! empty( $value['name'] ) && ! WCUtils::is_blank( $id ) ) {

		$id = usces_update_system_option( 'usces_payment_method', $id, $value );

		return $id;
	}
	return -2;
}

/**
 * Delete payment
 *
 * @return mixed
 */
function del_payment_method() {

	$id = wp_unslash( $_POST['id'] );

	if ( ! WCUtils::is_blank( $id ) ) {

		usces_del_system_option( 'usces_payment_method', $id );

		return $id;
	}
	return false;
}

/**
 * Sort payment method
 *
 * @return mixed
 */
function sort_payment_method() {

	return usces_sort_system_option( 'usces_payment_method', wp_unslash( $_POST['idstr'] ) );
}

/**
 * Add delivery method
 *
 * @return mixed
 */
function add_delivery_method() {
	$options = get_option( 'usces', array() );
	$name    = trim( wp_unslash( $_POST['name'] ) );
	$ids     = array();
	foreach ( (array) $options['delivery_method'] as $deli ) {
		$ids[] = (int) $deli['id'];
	}
	if ( ! empty( $ids ) ) {
		rsort( $ids );
		$newid = $ids[0] + 1;
	} else {
		$newid = 0;
	}
	$index                                        = ( isset( $options['delivery_method'] ) && is_array( $options['delivery_method'] ) ) ? count( $options['delivery_method'] ) : 0;
	$options['delivery_method'][ $index ]['id']   = $newid;
	$options['delivery_method'][ $index ]['name'] = esc_js( $name );
	$options['delivery_method'][ $index ]['time'] = wel_esc_script( str_replace( "\r\n", "\n", wp_unslash( $_POST['time'] ) ) );
	$options['delivery_method'][ $index ]['time'] = str_replace( "\r", "\n", $options['delivery_method'][ $index ]['time'] );
	$options['delivery_method'][ $index ]['charge']        = (int) wp_unslash( $_POST['charge'] );
	$options['delivery_method'][ $index ]['days']          = (int) wp_unslash( $_POST['days'] );
	$options['delivery_method'][ $index ]['nocod']         = wp_unslash( $_POST['nocod'] );
	$options['delivery_method'][ $index ]['intl']          = wp_unslash( $_POST['intl'] );
	$options['delivery_method'][ $index ]['cool_category'] = (int) wp_unslash( $_POST['cool_category'] );
	update_option( 'usces', $options );

	$response = array(
		'id'            => $newid,
		'name'          => esc_js( stripslashes( $name ) ),
		'time'          => stripslashes( $options['delivery_method'][ $index ]['time'] ),
		'charge'        => $options['delivery_method'][ $index ]['charge'],
		'days'          => $options['delivery_method'][ $index ]['days'],
		'nocod'         => $options['delivery_method'][ $index ]['nocod'],
		'intl'          => $options['delivery_method'][ $index ]['intl'],
		'cool_category' => $options['delivery_method'][ $index ]['cool_category'],
	);

	return json_encode( $response );
}

/**
 * Update delivery method
 *
 * @return mixed
 */
function update_delivery_method() {
	$options = get_option( 'usces', array() );
	$name    = trim( wp_unslash( $_POST['name'] ) );
	$id      = (int) wp_unslash( $_POST['id'] );
	$charge  = (int) wp_unslash( $_POST['charge'] );
	$index   = 0;
	$ct      = ( isset( $options['delivery_method'] ) && is_array( $options['delivery_method'] ) ) ? count( $options['delivery_method'] ) : 0;
	for ( $i = 0; $i < $ct; $i++ ) {
		if ( $options['delivery_method'][ $i ]['id'] === $id ) {
			$index = $i;
		}
	}
	$options['delivery_method'][ $index ]['name']          = esc_js( $name );
	$options['delivery_method'][ $index ]['charge']        = $charge;
	$options['delivery_method'][ $index ]['time']          = wel_esc_script( str_replace( "\r\n", "\n", wp_unslash( $_POST['time'] ) ) );
	$options['delivery_method'][ $index ]['time']          = str_replace( "\r", "\n", $options['delivery_method'][ $index ]['time'] );
	$options['delivery_method'][ $index ]['days']          = (int) wp_unslash( $_POST['days'] );
	$options['delivery_method'][ $index ]['nocod']         = wp_unslash( $_POST['nocod'] );
	$options['delivery_method'][ $index ]['intl']          = wp_unslash( $_POST['intl'] );
	$options['delivery_method'][ $index ]['cool_category'] = (int) wp_unslash( $_POST['cool_category'] );
	update_option( 'usces', $options );

	$response = array(
		'id'            => $id,
		'name'          => esc_js( stripslashes( $name ) ),
		'time'          => stripslashes( $options['delivery_method'][ $index ]['time'] ),
		'charge'        => $options['delivery_method'][ $index ]['charge'],
		'days'          => $options['delivery_method'][ $index ]['days'],
		'nocod'         => $options['delivery_method'][ $index ]['nocod'],
		'intl'          => $options['delivery_method'][ $index ]['intl'],
		'cool_category' => $options['delivery_method'][ $index ]['cool_category'],
	);

	return json_encode( $response );
}

/**
 * Delete delivery method
 *
 * @return mixed
 */
function delete_delivery_method() {
	$options = get_option( 'usces', array() );
	$id      = (int) wp_unslash( $_POST['id'] );
	$index   = 0;
	$ct      = ( isset( $options['delivery_method'] ) && is_array( $options['delivery_method'] ) ) ? count( $options['delivery_method'] ) : 0;
	for ( $i = 0; $i < $ct; $i++ ) {
		if ( $options['delivery_method'][ $i ]['id'] === $id ) {
			$index = $i;
		}
	}
	array_splice( $options['delivery_method'], $index, 1 );
	update_option( 'usces', $options );

	$response = array( 'id' => $id );

	return json_encode( $response );
}

/**
 * Moveup delivery method
 *
 * @return mixed
 */
function moveup_delivery_method() {
	$options     = get_option( 'usces', array() );
	$selected_id = (int) wp_unslash( $_POST['id'] );
	$index       = 0;
	$ct          = ( isset( $options['delivery_method'] ) && is_array( $options['delivery_method'] ) ) ? count( $options['delivery_method'] ) : 0;
	for ( $i = 0; $i < $ct; $i++ ) {
		if ( $options['delivery_method'][ $i ]['id'] === $selected_id ) {
			$index = $i;
		}
	}
	if ( 0 !== $index ) {
		$from_index = $index;
		$to_index   = $index - 1;
		$from_dm    = $options['delivery_method'][ $from_index ];
		$to_dm      = $options['delivery_method'][ $to_index ];
		for ( $i = 0; $i < $ct; $i++ ) {
			if ( $i === $to_index ) {
				$options['delivery_method'][ $i ] = $from_dm;
			} elseif ( $i === $from_index ) {
				$options['delivery_method'][ $i ] = $to_dm;
			}
		}
		update_option( 'usces', $options );
	}

	$id            = '';
	$name          = '';
	$charge        = '';
	$time          = '';
	$days          = '';
	$nocod         = '';
	$intl          = '';
	$cool_category = '';
	for ( $i = 0; $i < $ct; $i++ ) {
		$id            .= $options['delivery_method'][ $i ]['id'] . ',';
		$name          .= $options['delivery_method'][ $i ]['name'] . ',';
		$charge        .= $options['delivery_method'][ $i ]['charge'] . ',';
		$time          .= $options['delivery_method'][ $i ]['time'] . ',';
		$days          .= $options['delivery_method'][ $i ]['days'] . ',';
		$nocod         .= $options['delivery_method'][ $i ]['nocod'] . ',';
		$intl          .= $options['delivery_method'][ $i ]['intl'] . ',';
		$cool_category .= isset( $options['delivery_method'][ $i ]['cool_category'] ) ? ( $options['delivery_method'][ $i ]['cool_category'] . ',' ) : '0,';
	}

	$response = array(
		'id'            => rtrim( $id, ',' ),
		'name'          => rtrim( $name, ',' ),
		'time'          => rtrim( $time, ',' ),
		'charge'        => rtrim( $charge, ',' ),
		'days'          => rtrim( $days, ',' ),
		'nocod'         => rtrim( $nocod, ',' ),
		'intl'          => rtrim( $intl, ',' ),
		'cool_category' => rtrim( $cool_category, ',' ),
		'selected'      => $selected_id,
	);

	return json_encode( $response );
}

/**
 * Movedown delivery method
 *
 * @return mixed
 */
function movedown_delivery_method() {
	$options     = get_option( 'usces', array() );
	$selected_id = (int) wp_unslash( $_POST['id'] );
	$index       = 0;
	$ct          = ( isset( $options['delivery_method'] ) && is_array( $options['delivery_method'] ) ) ? count( $options['delivery_method'] ) : 0;
	for ( $i = 0; $i < $ct; $i++ ) {
		if ( $options['delivery_method'][ $i ]['id'] === $selected_id ) {
			$index = $i;
		}
	}
	if ( $index < $ct - 1 ) {
		$from_index = $index;
		$to_index   = $index + 1;
		$from_dm    = $options['delivery_method'][ $from_index ];
		$to_dm      = $options['delivery_method'][ $to_index ];
		for ( $i = 0; $i < $ct; $i++ ) {
			if ( $i === $to_index ) {
				$options['delivery_method'][ $i ] = $from_dm;
			} elseif ( $i === $from_index ) {
				$options['delivery_method'][ $i ] = $to_dm;
			}
		}
		update_option( 'usces', $options );
	}

	$id            = '';
	$name          = '';
	$charge        = '';
	$time          = '';
	$days          = '';
	$nocod         = '';
	$intl          = '';
	$cool_category = '';
	for ( $i = 0; $i < $ct; $i++ ) {
		$id            .= $options['delivery_method'][ $i ]['id'] . ',';
		$name          .= $options['delivery_method'][ $i ]['name'] . ',';
		$charge        .= $options['delivery_method'][ $i ]['charge'] . ',';
		$time          .= $options['delivery_method'][ $i ]['time'] . ',';
		$days          .= $options['delivery_method'][ $i ]['days'] . ',';
		$nocod         .= $options['delivery_method'][ $i ]['nocod'] . ',';
		$intl          .= $options['delivery_method'][ $i ]['intl'] . ',';
		$cool_category .= isset( $options['delivery_method'][ $i ]['cool_category'] ) ? ( $options['delivery_method'][ $i ]['cool_category'] . ',' ) : '0,';
	}
	$response = array(
		'id'            => rtrim( $id, ',' ),
		'name'          => rtrim( $name, ',' ),
		'time'          => rtrim( $time, ',' ),
		'charge'        => rtrim( $charge, ',' ),
		'days'          => rtrim( $days, ',' ),
		'nocod'         => rtrim( $nocod, ',' ),
		'intl'          => rtrim( $intl, ',' ),
		'cool_category' => rtrim( $cool_category, ',' ),
		'selected'      => $selected_id,
	);

	return json_encode( $response );
}

/**
 * Add shipping charge
 *
 * @return string
 */
function add_shipping_charge() {
	$options = get_option( 'usces', array() );
	$name    = trim( wp_unslash( $_POST['name'] ) );
	$ids     = array();
	foreach ( (array) $options['shipping_charge'] as $charge ) {
		$ids[] = (int) $charge['id'];
	}
	if ( ! empty( $ids ) ) {
		rsort( $ids );
		$newid = $ids[0] + 1;
	} else {
		$newid = 0;
	}
	$index         = ( isset( $options['shipping_charge'] ) && is_array( $options['shipping_charge'] ) ) ? count( $options['shipping_charge'] ) : 0;
	$target_market = ( isset( $options['system']['target_market'] ) && ! empty( $options['system']['target_market'] ) ) ? $options['system']['target_market'] : usces_get_local_target_market();
	foreach ( (array) $target_market as $tm ) {
		$prefs = get_usces_states( $tm );
		array_shift( $prefs );
		$value = wp_unslash( $_POST[ 'value_' . $tm ] );

		$options['shipping_charge'][ $index ]['id']   = $newid;
		$options['shipping_charge'][ $index ]['name'] = esc_js( $name );
		$prefs_count                                  = count( $prefs );
		for ( $i = 0; $i < $prefs_count; $i++ ) {
			$options['shipping_charge'][ $index ][ $tm ][ $prefs[ $i ] ] = (float) $value[ $i ];
		}
	}
	update_option( 'usces', $options );

	$res = (string) $newid;
	return $res;
}

/**
 * Update shipping charge
 *
 * @return string
 */
function update_shipping_charge() {
	$options = get_option( 'usces', array() );
	$name    = trim( wp_unslash( $_POST['name'] ) );
	$id      = (int) wp_unslash( $_POST['id'] );
	$index   = 0;

	$shipping_charge_count = ( isset( $options['shipping_charge'] ) && is_array( $options['shipping_charge'] ) ) ? count( $options['shipping_charge'] ) : 0;
	for ( $i = 0; $i < $shipping_charge_count; $i++ ) {
		if ( $options['shipping_charge'][ $i ]['id'] === $id ) {
			$index = $i;
		}
	}
	$options['shipping_charge'][ $index ]['name'] = esc_js( $name );
	$target_market = ( isset( $options['system']['target_market'] ) && ! empty( $options['system']['target_market'] ) ) ? $options['system']['target_market'] : usces_get_local_target_market();
	foreach ( (array) $target_market as $tm ) {
		$prefs = get_usces_states( $tm );
		array_shift( $prefs );
		$value       = wp_unslash( $_POST[ 'value_' . $tm ] );
		$prefs_count = count( $prefs );
		for ( $i = 0; $i < $prefs_count; $i++ ) {
			$options['shipping_charge'][ $index ][ $tm ][ $prefs[ $i ] ] = (float) $value[ $i ];
		}
	}
	$options = apply_filters( 'usces_filter_update_shipping_charge', $options, $index, $name, $id );

	update_option( 'usces', $options );

	$res = (string) $id;
	return $res;
}

/**
 * Delete shipping charge
 *
 * @return string
 */
function delete_shipping_charge() {
	$options = get_option( 'usces', array() );
	$id      = (int) wp_unslash( $_POST['id'] );
	$index   = 0;

	$shipping_charge_count = ( isset( $options['shipping_charge'] ) && is_array( $options['shipping_charge'] ) ) ? count( $options['shipping_charge'] ) : 0;
	for ( $i = 0; $i < $shipping_charge_count; $i++ ) {
		if ( $options['shipping_charge'][ $i ]['id'] === $id ) {
			$index = $i;
		}
	}
	array_splice( $options['shipping_charge'], $index, 1 );
	update_option( 'usces', $options );

	$res = (string) $id;
	return $res;
}

/**
 * Add delivery days
 *
 * @return string
 */
function add_delivery_days() {
	$options = get_option( 'usces', array() );
	$name    = trim( wp_unslash( $_POST['name'] ) );
	$ids     = array();
	foreach ( (array) $options['delivery_days'] as $charge ) {
		$ids[] = (int) $charge['id'];
	}
	if ( ! empty( $ids ) ) {
		rsort( $ids );
		$newid = $ids[0] + 1;
	} else {
		$newid = 0;
	}
	$index         = ( isset( $options['delivery_days'] ) && is_array( $options['delivery_days'] ) ) ? count( $options['delivery_days'] ) : 0;
	$target_market = ( isset( $options['system']['target_market'] ) && ! empty( $options['system']['target_market'] ) ) ? $options['system']['target_market'] : usces_get_local_target_market();
	foreach ( (array) $target_market as $tm ) {
		$prefs = get_usces_states( $tm );
		array_shift( $prefs );
		$value = wp_unslash( $_POST[ 'value_' . $tm ] );

		$options['delivery_days'][ $index ]['id']   = $newid;
		$options['delivery_days'][ $index ]['name'] = esc_js( $name );
		$prefs_count                                = count( $prefs );
		for ( $i = 0; $i < $prefs_count; $i++ ) {
			$options['delivery_days'][ $index ][ $tm ][ $prefs[ $i ] ] = (int) $value[ $i ];
		}
	}
	update_option( 'usces', $options );

	$res = (string) $newid;
	return $res;
}

/**
 * Update delivery days
 *
 * @return string
 */
function update_delivery_days() {
	$options = get_option( 'usces', array() );
	$name    = trim( wp_unslash( $_POST['name'] ) );
	$id      = (int) wp_unslash( $_POST['id'] );
	$index   = 0;

	$delivery_days_count = ( isset( $options['delivery_days'] ) && is_array( $options['delivery_days'] ) ) ? count( $options['delivery_days'] ) : 0;
	for ( $i = 0; $i < $delivery_days_count; $i++ ) {
		if ( $options['delivery_days'][ $i ]['id'] === $id ) {
			$index = $i;
		}
	}
	$options['delivery_days'][ $index ]['name'] = esc_js( $name );
	$target_market = ( isset( $options['system']['target_market'] ) && ! empty( $options['system']['target_market'] ) ) ? $options['system']['target_market'] : usces_get_local_target_market();
	foreach ( (array) $target_market as $tm ) {
		$prefs = get_usces_states( $tm );
		array_shift( $prefs );
		$value       = wp_unslash( $_POST[ 'value_' . $tm ] );
		$prefs_count = count( $prefs );
		for ( $i = 0; $i < $prefs_count; $i++ ) {
			$options['delivery_days'][ $index ][ $tm ][ $prefs[ $i ] ] = (int) $value[ $i ];
		}
	}
	update_option( 'usces', $options );

	$res = (string) $id;
	return $res;
}

/**
 * Delete delivery days
 *
 * @return string
 */
function delete_delivery_days() {
	$options             = get_option( 'usces', array() );
	$id                  = (int) wp_unslash( $_POST['id'] );
	$index               = 0;
	$delivery_days_count = ( isset( $options['delivery_days'] ) && is_array( $options['delivery_days'] ) ) ? count( $options['delivery_days'] ) : 0;
	for ( $i = 0; $i < $delivery_days_count; $i++ ) {
		if ( $options['delivery_days'][ $i ]['id'] === $id ) {
			$index = $i;
		}
	}
	array_splice( $options['delivery_days'], $index, 1 );
	update_option( 'usces', $options );

	$res = (string) $id;
	return $res;
}

/**
 * Shop Options Ajax
 *
 * @return void
 */
function shop_options_ajax() {

	check_admin_referer( 'admin_delivery', 'wc_nonce' );
	if ( 4 > usces_get_admin_user_level() ) {
		die('user_level');
	}

	if ( isset( $_POST['action'] ) && 'shop_options_ajax' !== wp_unslash( $_POST['action'] ) ) {
		die(0);
	}

	$mode = ( isset( $_POST['mode'] ) ) ? wp_unslash( $_POST['mode'] ) : '';
	switch ( $mode ) {
		case 'add_delivery_method':
			$res = add_delivery_method();
			break;
		case 'update_delivery_method':
			$res = update_delivery_method();
			break;
		case 'delete_delivery_method':
			$res = delete_delivery_method();
			break;
		case 'moveup_delivery_method':
			$res = moveup_delivery_method();
			break;
		case 'movedown_delivery_method':
			$res = movedown_delivery_method();
			break;
		case 'add_shipping_charge':
			$res = add_shipping_charge();
			break;
		case 'update_shipping_charge':
			$res = update_shipping_charge();
			break;
		case 'delete_shipping_charge':
			$res = delete_shipping_charge();
			break;
		case 'add_delivery_days':
			$res = add_delivery_days();
			break;
		case 'update_delivery_days':
			$res = update_delivery_days();
			break;
		case 'delete_delivery_days':
			$res = delete_delivery_days();
			break;
	}

	die( $res );
}
