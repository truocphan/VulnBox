<?php
/**
 * The Logger class.
 *
 * @since 2.7.4
 * @package  Welcart
 * @author   Collne Inc.
 * @since    2.7.4
 */

/**
 *  The Logger class
 */
class Logger {
	/**
	 * The instance of the Logger class.
	 *
	 * @since 2.7.4
	 * @access public
	 * @var object $logger The Logger instance.
	 */
	public static $logger;

	/**
	 * The entity type.
	 *
	 * @since 2.7.4
	 * @access private
	 * @var string $entity_type The entity type.
	 */
	private $entity_type;

	/**
	 * The action.
	 *
	 * @since 2.7.4
	 * @access private
	 * @var string $action The action.
	 */
	private $action;

	/**
	 * The entity id.
	 *
	 * @since 2.7.4
	 * @access private
	 * @var string $entity_id The entity id.
	 */
	private $entity_id;

	/**
	 * The ignored flag
	 *
	 * @since 2.7.4
	 * @access private
	 * @var boolean $ignored The ignored flag.
	 */
	private $ignored = false;

	/**
	 * The differed flag
	 *
	 * @since 2.7.4
	 * @access private
	 * @var boolean $differed The differed flag.
	 */
	private $differed = false;

	/**
	 * The comparable flag
	 *
	 * @since 2.7.4
	 * @access private
	 * @var boolean $compared_mode The comparable flag.
	 */
	private $compared_mode = true;

	/**
	 * The data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @var array $data The data.
	 */
	private $data = array();

	/**
	 * The advance data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @var array $advance_data The data.
	 */
	private $advance_data = array();

	/**
	 * Constructor
	 *
	 * @param integer $entity_id The entity id.
	 * @param string  $screen The screen id.
	 * @param string  $action The action.
	 */
	private function __construct( $entity_id, $screen, $action ) {
		$this->entity_id = $entity_id;
		$this->screen    = $screen;
		$this->action    = $action;
	}

	/**
	 * Save a log into database.
	 *
	 * @param integer $entity_id The entity id.
	 * @param string  $screen The screen id.
	 * @param string  $action The action.
	 * @param array   $advance_data The advance data.
	 */
	public static function log( $entity_id, $screen, $action, $advance_data = array() ) {
		$logger = self::get_instance( $entity_id, $screen, $action );
		if ( $logger->is_enabled() ) {
			$logger->set_advance_data( $advance_data );
			$logger->set_after_data();

			$logger->prepare_display_data();

			$logger->save();
		}
	}

	/**
	 * Set entity id.
	 *
	 * @since 2.7.4
	 * @access public
	 * @param integer $entity_id The entity id.
	 * @param string  $screen The screen id.
	 * @param string  $action The action.
	 * @return Object $logger The Logger object.
	 */
	public static function start( $entity_id, $screen, $action ) {
		$logger = self::get_instance( $entity_id, $screen, $action );
		if ( $logger->is_enabled() ) {
			$logger->set_before_data();
		}

		return $logger;
	}

	/**
	 * Compare the before data and the after data.
	 * If they are diffirent, insert the current log into database
	 *
	 * @since 2.7.4
	 * @access public
	 */
	public function flush() {
		if ( $this->is_enabled() ) {
			$this->set_after_data();
			$this->prepare_display_data();

			$logged = ! $this->ignored;
			if ( $logged ) {
				$this->save();
			}
		}
	}

	/**
	 * Save an admin log into the database.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function save() {
		global $wpdb;

		$current_user    = wp_get_current_user();
		$author          = ( isset( $current_user->user_login ) ) ? $current_user->user_login : '';
		$datetime        = get_date_from_gmt( gmdate( 'Y-m-d H:i:s', time() ) );
		$data            = maybe_serialize( $this->data );
		$admin_log_table = $wpdb->prefix . 'usces_admin_log';

		$wpdb->insert(
			$admin_log_table,
			array(
				'author'    => $author,
				'message'   => $this->message(),
				'data'      => $data,
				'entity_id' => $this->entity_id,
				'action'    => $this->action,
				'datetime'  => $datetime,
				'screen'    => $this->screen,
			)
		);
	}

	/**
	 * Prepare the display data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function prepare_display_data() {
		$comparable = ! empty( $this->data['before'] ) && ! empty( $this->data['after'] );
		$this->set_compared_mode( $comparable );
		$display_data = $this->prepare_display_fields();

		switch ( $this->action ) {
			case 'update':
				$this->ignored          = ! $this->differed;
				$this->data['differed'] = $this->differed;
				break;
			case 'delete':
				unset( $this->data['after'] );
				break;
			case 'create':
				unset( $this->data['before'] );
				break;
		}

		$this->data['display_data'] = $display_data;
	}

	/**
	 * Get list of the display fields
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array $display_fields The display fields.
	 */
	private function get_display_fields() {
		switch ( $this->screen ) {
			case 'orderlist':
			case 'ordernew':
			case 'orderedit':
				$display_fields = $this->get_order_display_fields();
				break;
			case 'memberlist':
			case 'membernew':
			case 'memberedit':
				$display_fields = $this->get_member_display_fields();
				break;
		}

		return $display_fields;
	}

	/**
	 * Set the compare mode.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param boolean $mode The mode.
	 * @return void
	 */
	private function set_compared_mode( $mode ) {
		$this->compared_mode = $mode;
	}

	/**
	 * Set the before entity data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function set_before_data() {
		$this->data['before'] = $this->get_entity_data();
	}

	/**
	 * Set the after entity data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function set_after_data() {
		$this->data['after'] = $this->get_entity_data();
	}

	/**
	 * Set the advance data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $advance_data The advance data.
	 * @return void
	 */
	private function set_advance_data( $advance_data ) {
		$this->advance_data = $advance_data;
	}

	/**
	 * Set entity type.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return void
	 */
	private function set_entity_type() {
		switch ( $this->screen ) {
			case 'orderlist':
			case 'ordernew':
			case 'orderedit':
				$this->entity_type = 'order';
				break;
			case 'memberlist':
			case 'membernew':
			case 'memberedit':
				$this->entity_type = 'member';
				break;
			default:
				$this->entity_type = '';
		}
	}

	/**
	 * Get the entity data from database.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array $entity_data The entity data.
	 */
	private function get_entity_data() {
		global $usces;
		switch ( $this->entity_type ) {
			case 'order':
				$entity_data = $this->get_order_data();
				break;
			case 'member':
				$entity_data = $this->get_member_data();
				break;
			default:
				$entity_data = array();
		}

		if ( ! empty( $this->advance_data ) ) {
			foreach ( $this->advance_data as $field_name => $field_data ) {
				$entity_data[ $field_name ] = $field_data;
			}
		}

		return $entity_data;
	}

	/**
	 * Get the order data by the order id.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array $order_data The order data.
	 */
	private function get_order_data() {
		global $usces;
		$order_id                      = $this->entity_id;
		$order_data                    = $usces->get_order_data( $order_id, 'direct' );
		$cart                          = usces_get_ordercartdata( $order_id );
		$order_data['order_cart']      = serialize( $cart );
		$order_data['custom_order']    = $this->get_custom_order_data( 'order', $order_id );
		$order_data['order_summary']   = $this->get_order_summary_data( $order_data );
		$order_data['custom_customer'] = $this->get_custom_order_data( 'customer', $order_id );
		$order_data['custom_delivery'] = $this->get_custom_order_data( 'delivery', $order_id );
		$other_custom_order            = $this->get_custom_order_data( 'other', $order_id );
		$order_data                    = array_merge( $order_data, $other_custom_order );

		return $order_data;
	}

	/**
	 * Get the member data by the member id.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array $member_data The member data.
	 */
	private function get_member_data() {
		global $usces;
		$member_id   = $this->entity_id;
		$member_data = $usces->get_member_info( $member_id );

		$member_data['custom_member']       = $this->get_custom_member_data( 'member', $member_id );
		$member_data['custom_admin_member'] = $this->get_custom_member_data( 'admin_member', $member_id );

		return $member_data;
	}

	/**
	 * Get the customer member data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param string  $member_field_type  "member" or "admin_member".
	 * @param integer $member_id The member id.
	 * @return array $custom_member_data The custom member data.
	 */
	private function get_custom_member_data( $member_field_type, $member_id ) {
		global $usces;
		$custom_member_data  = array();
		$custom_member_metas = usces_has_custom_field_meta( $member_field_type );

		if ( is_array( $custom_member_metas ) ) {
			$is_admin_member_field = 'admin_member' === $member_field_type;
			$member_field_prefix   = ( $is_admin_member_field ) ? 'admb_' : 'csmb_';

			$keys = array_keys( $custom_member_metas );
			foreach ( $keys as $key ) {
				$custom_member_meta_key     = $member_field_prefix . $key;
				$custom_member_data[ $key ] = maybe_unserialize( $usces->get_member_meta_value( $custom_member_meta_key, $member_id ) );
			}
		}

		return $custom_member_data;
	}

	/**
	 * Get the customer order data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param string  $order_field_type  "order" or "customer".
	 * @param integer $order_id The member id.
	 * @return array $custom_order_data The customer order data.
	 */
	private function get_custom_order_data( $order_field_type, $order_id ) {
		global  $usces;
		$custom_order_data = array();
		$is_other_field    = 'other' === $order_field_type;
		if ( $is_other_field ) {
			$custom_order_metas = $this->get_other_custom_order_metas();
		} else {
			$custom_order_metas = usces_has_custom_field_meta( $order_field_type );
		}

		if ( is_array( $custom_order_metas ) ) {
			switch ( $order_field_type ) {
				case 'order':
					$order_field_prefix = 'csod_';
					break;
				case 'customer':
					$order_field_prefix = 'cscs_';
					break;
				case 'delivery':
					$order_field_prefix = 'csde_';
					break;
				case 'other':
					$order_field_prefix = '';
					break;
				default:
					$order_field_prefix = '';
			}

			$keys = array_keys( $custom_order_metas );
			foreach ( $keys as $key ) {
				$custom_order_meta_key     = $order_field_prefix . $key;
				$custom_order_data[ $key ] = maybe_unserialize( $usces->get_order_meta_value( $custom_order_meta_key, $order_id ) );
			}
		}

		return $custom_order_data;
	}

	/**
	 * Prepare data for the order summary.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $order The order data.
	 * @return array $order_summary_data The order amount data.
	 */
	private function get_order_summary_data( $order ) {
		$order_summary_data   = array();
		$display_fields       = $this->get_order_display_fields();
		$order_summary_fields = $display_fields['order_summary']['fields'];
		foreach ( $order_summary_fields as $field_name => $order_field ) {
			$order_summary_data[ $field_name ] = ! empty( $order[ $field_name ] ) ? $order[ $field_name ] : '';
		}

		return $order_summary_data;
	}
	/**
	 * Get the other custom order fields.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array $other_custom_order_metas The meta fields.
	 */
	private function get_other_custom_order_metas() {
		$display_fields           = $this->get_order_display_fields();
		$other_custom_order_metas = array(
			'order_memo'       => $display_fields['order_memo'],
			'delivery_company' => $display_fields['delivery_company'],
			'tracking_number'  => $display_fields['tracking_number'],
		);

		return $other_custom_order_metas;
	}

	/**
	 * The message.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return string $message The message.
	 */
	private function message() {
		switch ( $this->action ) {
			case 'create':
				$message = __( 'Created the %1$s #%2$d successfully.', 'usces' );
				break;
			case 'update':
				$message = __( 'Updated the %1$s #%2$d successfully.', 'usces' );
				break;
			case 'delete':
				$message = __( 'Deleted the %1$s #%2$d successfully.', 'usces' );
				break;
			default:
				$message = '';
		}

		$entity_type = $this->get_entity_type_label();
		$message     = sprintf(
			$message,
			$entity_type,
			$this->entity_id
		);

		return $message;
	}

	/**
	 * Create the instance of the Logger class.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param integer $entity_id The entity id.
	 * @param string  $screen The screen id.
	 * @param string  $action The action.
	 * @return object $logger The logger instance.
	 */
	private static function get_instance( $entity_id, $screen, $action ) {
		$logger = new self( $entity_id, $screen, $action );
		$logger->set_entity_type();
		return $logger;
	}

	/**
	 * Prepare data for display fields.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array $display_data The display data.
	 */
	private function prepare_display_fields() {
		$data         = $this->data;
		$display_data = array();
		$differed     = false;
		if ( ! empty( $data['before'] ) || ! empty( $data['after'] ) ) {
			$display_fields = $this->get_display_fields();
			$before_data    = ! empty( $data['before'] ) ? $data['before'] : '';
			$after_data     = ! empty( $data['after'] ) ? $data['after'] : '';
			foreach ( $display_fields as $key => $display_field ) {
				$before_value = ! empty( $before_data[ $key ] ) ? $before_data[ $key ] : '';
				$after_value  = ! empty( $after_data[ $key ] ) ? $after_data[ $key ] : '';
				$field_data   = $this->prepare_field_data( $display_field, $before_value, $after_value );
				if ( ! empty( $field_data ) ) {
					$display_data[ $key ] = $field_data;
					$differed             = $differed || ! empty( $field_data['is_diff'] );
				}
			}
		}

		$this->differed = $differed;
		return $display_data;
	}

	/**
	 * Prepare field data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $field The field key.
	 * @param mixed $before_value The before value.
	 * @param mixed $after_value The after value.
	 * @return array $field_data The field data.
	 */
	private function prepare_field_data( $field, $before_value, $after_value ) {
		$field_data = array();

		$has_callback = isset( $field['field_data_callback'] );
		if ( $has_callback ) {
			$callback = $field['field_data_callback'];
			if ( method_exists( $this, $callback ) ) {
				$field_data = $this->{$callback}( $field, $before_value, $after_value );
			}

			return $field_data;
		} else {
			$is_repeater = isset( $field['is_repeater'] );
			$is_group    = isset( $field['is_group'] );

			if ( $is_repeater ) {
				$field_data = $this->prepare_repeater_data( $field, $before_value, $after_value );
			} elseif ( $is_group ) {
				$field_data = $this->prepare_group_data( $field, $before_value, $after_value );
			} else {
				$field_data = $this->prepare_plain_data( $field, $before_value, $after_value );
			}

			return $field_data;
		}
	}

	/**
	 * Compare the repeater field.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $field The field key.
	 * @param mixed $before_value The before value.
	 * @param mixed $after_value The after value.
	 * @return array $field_data The field diff.
	 */
	private function prepare_repeater_data( $field, $before_value, $after_value ) {
		$field_data   = array();
		$parent_group = $field['group'];
		$before_data  = maybe_unserialize( $before_value );
		$after_data   = maybe_unserialize( $after_value );
		if ( ! empty( $before_data ) || ! empty( $after_data ) ) {
			$repeater_data_rows = array();
			$is_key_and_value   = isset( $field['is_key_and_value'] );
			$row_keys           = array();
			if ( $is_key_and_value ) {
				if ( empty( $row_keys ) ) {
					if ( ! empty( $before_data ) ) {
						$row_keys = array_keys( $before_data );
					}

					if ( ! empty( $after_data ) ) {
						$row_keys = array_keys( $after_data );
					}
				}
			} else {
				if ( ! is_array( $before_data ) ) {
					$is_before_data_much_more = false;
				} elseif ( ! is_array( $after_data ) ) {
					$is_before_data_much_more = true;
				} else {
					$is_before_data_much_more = count( $before_data ) > count( $after_data );
				}

				if ( $is_before_data_much_more ) {
					$row_keys = array_keys( $before_data );
				} else {
					$row_keys = array_keys( $after_data );
				}
			}

			$is_diff = false;
			foreach ( $row_keys as $index ) {
				$before_repeater_row = isset( $before_data[ $index ] ) ? $before_data[ $index ] : '';

				if ( $is_key_and_value ) {
					$before_repeater_row = maybe_unserialize( $before_repeater_row );
					$sub_field           = array(
						'group' => $parent_group,
						'label' => $index,
					);

					$after_repeater_row = isset( $after_data[ $index ] ) ? $after_data[ $index ] : '';
					$after_repeater_row = maybe_unserialize( $after_repeater_row );

					$repeater_data_rows[ $index ] = $this->prepare_field_data( $sub_field, $before_repeater_row, $after_repeater_row );
					$is_diff                      = $is_diff || ! empty( $repeater_data_rows[ $index ]['is_diff'] );
				} else {
					$sub_fields = $field['fields'];
					foreach ( $sub_fields as $key => $sub_field ) {
						$sub_field['group']                   = $parent_group;
						$before_repeater_col                  = isset( $before_repeater_row[ $key ] ) ? $before_repeater_row[ $key ] : '';
						$after_repeater_col                   = isset( $after_data[ $index ][ $key ] ) ? $after_data[ $index ][ $key ] : '';
						$repeater_data_rows[ $index ][ $key ] = $this->prepare_field_data( $sub_field, $before_repeater_col, $after_repeater_col );
						$is_diff                              = $is_diff || ! empty( $repeater_data_rows[ $index ][ $key ]['is_diff'] );
					}
				}
			}

			$field_data = array(
				'field'            => $field,
				'is_diff'          => $is_diff,
				'is_repeater'      => true,
				'is_key_and_value' => $is_key_and_value,
				'rows'             => $repeater_data_rows,
			);
		}

		return $field_data;
	}

	/**
	 * Prepare the group field data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $field The field key.
	 * @param mixed $before_value The before value.
	 * @param mixed $after_value The after value.
	 * @return array $field_data The field data.
	 */
	private function prepare_group_data( $field, $before_value, $after_value ) {
		$field_data  = array();
		$before_data = maybe_unserialize( $before_value );
		$after_data  = maybe_unserialize( $after_value );
		if ( ! empty( $before_data ) || ! empty( $after_data ) ) {
			$sub_field_data = array();
			$sub_fields     = $field['fields'];
			$is_diff        = false;
			foreach ( $sub_fields as $key => $sub_field ) {
				$sub_before_value       = isset( $before_data[ $key ] ) ? $before_data[ $key ] : '';
				$sub_after_value        = isset( $after_data[ $key ] ) ? $after_data[ $key ] : '';
				$sub_field_data[ $key ] = $this->prepare_field_data( $sub_field, $sub_before_value, $sub_after_value );
				$is_diff                = $is_diff || ! empty( $sub_field_data[ $key ]['is_diff'] );
			}

			$field_data = array(
				'field'    => $field,
				'is_diff'  => $is_diff,
				'is_group' => true,
				'fields'   => $sub_field_data,
			);
		}

		return $field_data;
	}

	/**
	 * Prepare the plain field data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $field The field key.
	 * @param mixed $before_value The before value.
	 * @param mixed $after_value The after value.
	 * @return array $field_data The field data.
	 */
	private function prepare_plain_data( $field, $before_value, $after_value ) {
		if ( is_array( $before_value ) ) {
			$before_value = implode( ',', $before_value );
		}

		if ( is_array( $after_value ) ) {
			$after_value = implode( ',', $after_value );
		}

		$field_data = array(
			'field'  => $field,
			'before' => $before_value,
			'after'  => $after_value,
		);

		if ( $this->compared_mode ) {
			$field_data['is_diff'] = $before_value !== $after_value;
		}

		return $field_data;
	}

	/**
	 * Get the lable of the current entity type.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return string
	 */
	private function get_entity_type_label() {
		switch ( $this->entity_type ) {
			case 'order':
				return __( 'order', 'usces' );
			case 'member':
				return __( 'member', 'usces' );
		}
	}

	/**
	 * Prepare the acting_welcart_card field data.
	 *
	 * @since 2.7.4
	 * @access private
	 * @param array $display_field The display field key.
	 * @param mixed $before_value The before value.
	 * @param mixed $after_value The after value.
	 * @return array $field_data The field data.
	 */
	private function prepare_acting_welcart_card( $display_field, $before_value, $after_value ) {
		$field_data = array();
		if ( ! empty( $before_value ) || ! empty( $after_value ) ) {
			$welcartpay   = WELCARTPAY_SETTLEMENT::get_instance();
			$after_data   = array();
			$log          = usces_unserialize( $after_value['log'] );
			$operate_name = ( isset( $log['OperateId'] ) ) ? $welcartpay->get_operate_name( $log['OperateId'] ) : '';
			$amount       = ( isset( $log['Amount'] ) ) ? usces_crform( $log['Amount'], false, true, 'return', true ) : '';

			$after_data['TransactionDate'] = $after_value['datetime'];
			$after_data['TransactionId']   = $log['TransactionId'];
			$after_data['OperateId']       = $operate_name;
			$after_data['Amount']          = $amount;
			$after_data['ResponseCd']      = $log['ResponseCd'];

			$field_data = $this->prepare_group_data( $display_field, $before_value, $after_data );
		}

		return $field_data;
	}

	/**
	 * Check if the operator log function is enabled or not.
	 */
	private function is_enabled() {
		return OPERATION_LOG::is_enabled();
	}

	/**
	 * List of the order compared fields.
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array
	 */
	private function get_order_display_fields() {
		return array(
			'order_payment_name'    => array(
				'group' => 'other',
				'label' => __( 'payment method', 'usces' ),
			),
			'order_delivery_method' => array(
				'group' => 'other',
				'label' => __( 'shipping option', 'usces' ),
			),
			'order_delivery_date'   => array(
				'group' => 'other',
				'label' => __( 'Delivery date', 'usces' ),
			),
			'order_delivery_time'   => array(
				'group' => 'other',
				'label' => __( 'delivery time', 'usces' ),
			),
			'order_delidue_date'    => array(
				'group' => 'other',
				'label' => __( 'Shipping date', 'usces' ),
			),
			'order_email'           => array(
				'group' => 'other',
				'label' => __( 'e-mail adress', 'usces' ),
			),
			'order_name1'           => array(
				'group' => 'other',
				'label' => __( 'name', 'usces' ),
			),
			'order_name2'           => array(
				'group' => 'other',
				'label' => __( 'name', 'usces' ),
			),
			'order_name3'           => array(
				'group' => 'other',
				'label' => __( 'furigana', 'usces' ),
			),
			'order_name4'           => array(
				'group' => 'other',
				'label' => __( 'furigana', 'usces' ),
			),
			'order_zip'             => array(
				'group' => 'other',
				'label' => __( 'Zip/Postal Code', 'usces' ),
			),
			'order_pref'            => array(
				'group' => 'other',
				'label' => __( 'Province', 'usces' ),
			),
			'order_address1'        => array(
				'group' => 'other',
				'label' => __( 'city', 'usces' ),
			),
			'order_address2'        => array(
				'group' => 'other',
				'label' => __( 'numbers', 'usces' ),
			),
			'order_address3'        => array(
				'group' => 'other',
				'label' => __( 'building name', 'usces' ),
			),
			'order_tel'             => array(
				'group' => 'other',
				'label' => __( 'Phone number', 'usces' ),
			),
			'order_fax'             => array(
				'group' => 'other',
				'label' => __( 'FAX number', 'usces' ),
			),
			'order_note'            => array(
				'group' => 'other',
				'label' => __( 'Notes', 'usces' ),
			),
			'order_status'          => array(
				'group' => 'other',
				'label' => __( 'Status', 'usces' ),
			),
			'order_memo'            => array(
				'group' => 'other',
				'label' => __( 'Administrator Note', 'usces' ),
			),
			'delivery_company'      => array(
				'group' => 'other',
				'label' => __( 'Delivery company', 'usces' ),
			),
			'tracking_number'       => array(
				'group' => 'other',
				'label' => __( 'Tracking number', 'usces' ),
			),
			'order_delivery'        => array(
				'group'    => 'other',
				'label'    => __( 'shipping address', 'usces' ),
				'is_group' => true,
				'fields'   => array(
					'name1'    => array(
						'group' => 'other',
						'label' => __( 'name', 'usces' ),
					),
					'name2'    => array(
						'group' => 'other',
						'label' => __( 'name', 'usces' ),
					),
					'name3'    => array(
						'group' => 'other',
						'label' => __( 'furigana', 'usces' ),
					),
					'name4'    => array(
						'group' => 'other',
						'label' => __( 'furigana', 'usces' ),
					),
					'zipcode'  => array(
						'group' => 'other',
						'label' => __( 'Zip/Postal Code', 'usces' ),
					),
					'pref'     => array(
						'group' => 'other',
						'label' => __( 'Province', 'usces' ),
					),
					'address1' => array(
						'group' => 'other',
						'label' => __( 'city', 'usces' ),
					),
					'address2' => array(
						'group' => 'other',
						'label' => __( 'numbers', 'usces' ),
					),
					'address3' => array(
						'group' => 'other',
						'label' => __( 'building name', 'usces' ),
					),
					'tel'      => array(
						'group' => 'other',
						'label' => __( 'Phone number', 'usces' ),
					),
					'fax'      => array(
						'group' => 'other',
						'label' => __( 'FAX number', 'usces' ),
					),
				),
			),
			'custom_order'          => array(
				'is_repeater'      => true,
				'group'            => 'custom_order',
				'label'            => __( 'Custom order field', 'usces' ),
				'is_key_and_value' => true,
			),
			'custom_customer'       => array(
				'is_repeater'      => true,
				'group'            => 'custom_customer',
				'label'            => __( 'Custom customer field', 'usces' ),
				'is_key_and_value' => true,
			),
			'custom_delivery'       => array(
				'is_repeater'      => true,
				'group'            => 'custom_delivery',
				'label'            => __( 'Custom delivery field', 'usces' ),
				'is_key_and_value' => true,
			),
			'order_summary'         => array(
				'group'    => 'other',
				'label'    => __( 'Order summary', 'usces' ),
				'is_group' => true,
				'fields'   => array(
					'order_discount'        => array(
						'group' => 'other',
						'label' => __( 'Campaign discount', 'usces' ),
					),
					'order_shipping_charge' => array(
						'group' => 'other',
						'label' => __( 'Shipping', 'usces' ),
					),
					'order_cod_fee'         => array(
						'group' => 'other',
						'label' => __( 'COD fee', 'usces' ),
					),
					'order_usedpoint'       => array(
						'group' => 'other',
						'label' => __( 'Used points', 'usces' ),
					),
					'order_getpoint'        => array(
						'group' => 'other',
						'label' => __( 'granted points', 'usces' ),
					),
				),
			),
			'order_cart'            => array(
				'is_repeater' => true,
				'group'       => 'order_cart',
				'label'       => '',
				'row_label'   => __( 'Cart item', 'usces' ),
				'fields'      => array(
					'post_id'   => array(
						'group' => 'order_cart',
						'label' => __( 'Item ID', 'usces' ),
					),
					'item_name' => array(
						'group' => 'order_cart',
						'label' => __( 'item name', 'usces' ),
					),
					'sku_code'  => array(
						'group' => 'order_cart',
						'label' => __( 'SKU code', 'usces' ),
					),
					'price'     => array(
						'group' => 'order_cart',
						'label' => __( 'Price', 'usces' ),
					),
					'cprice'    => array(
						'group' => 'order_cart',
						'label' => __( 'normal price', 'usces' ),
					),
					'quantity'  => array(
						'group' => 'order_cart',
						'label' => __( 'Quantity', 'usces' ),
					),
					'options'   => array(
						'group'            => 'order_cart',
						'label'            => __( 'options for items', 'usces' ),
						'is_repeater'      => true,
						'is_key_and_value' => true,
					),
				),
			),
			'order_check'           => array(
				'is_group' => true,
				'group'    => 'other',
				'label'    => __( 'Show the mail/print field', 'usces' ),
				'fields'   => array(
					'ordermail'      => array(
						'group' => 'other',
						'label' => __( 'Mail for confirmation of order', 'usces' ),
					),
					'changemail'     => array(
						'group' => 'other',
						'label' => __( 'Mail for confiemation of change', 'usces' ),
					),
					'receiptmail'    => array(
						'group' => 'other',
						'label' => __( 'Mail for confirmation of transter', 'usces' ),
					),
					'mitumorimail'   => array(
						'group' => 'other',
						'label' => __( 'estimate mail', 'usces' ),
					),
					'cancelmail'     => array(
						'group' => 'other',
						'label' => __( 'Cancelling mail', 'usces' ),
					),
					'othermail'      => array(
						'group' => 'other',
						'label' => __( 'Other mail', 'usces' ),
					),
					'completionmail' => array(
						'group' => 'other',
						'label' => __( 'Mail for Shipping', 'usces' ),
					),
					'mitumoriprint'  => array(
						'group' => 'other',
						'label' => __( 'print out the estimate', 'usces' ),
					),
					'nohinprint'     => array(
						'group' => 'other',
						'label' => __( 'print out Delivery Statement', 'usces' ),
					),
					'billprint'      => array(
						'group' => 'other',
						'label' => __( 'Print Invoice', 'usces' ),
					),
					'receiptprint'   => array(
						'group' => 'other',
						'label' => __( 'Print Receipt', 'usces' ),
					),
				),
			),
			'acting_welcart_card'   => array(
				'is_group'            => true,
				'group'               => 'payment_transaction_logs',
				'label'               => __( 'Credit card transaction (WelcartPay)', 'usces' ),
				'field_data_callback' => 'prepare_acting_welcart_card',
				'fields'              => array(
					'TransactionDate' => array(
						'group' => 'payment_transaction_logs',
						'label' => __( 'Processing date', 'usces' ),
					),
					'TransactionId'   => array(
						'group' => 'payment_transaction_logs',
						'label' => __( 'Sequence number', 'usces' ),
					),
					'OperateId'       => array(
						'group' => 'payment_transaction_logs',
						'label' => __( 'Processing classification', 'usces' ),
					),
					'Amount'          => array(
						'group' => 'payment_transaction_logs',
						'label' => __( 'Amount', 'usces' ),
					),
					'ResponseCd'      => array(
						'group' => 'payment_transaction_logs',
						'label' => __( 'Result', 'usces' ),
					),
				),
			),
			'paygent_card'          => array(
				'is_group' => true,
				'group'    => 'payment_transaction_logs',
				'label'    => 'ペイジェント',
				'fields'   => array(
					'datetime'   => array(
						'group' => 'payment_transaction_logs',
						'label' => __( 'Processing date', 'usces' ),
					),
					'payment_id' => array(
						'group' => 'payment_transaction_logs',
						'label' => '決済ID',
					),
					'status'     => array(
						'group' => 'payment_transaction_logs',
						'label' => __( 'Processing classification', 'usces' ),
					),
					'amount'     => array(
						'group' => 'payment_transaction_logs',
						'label' => __( 'Amount', 'usces' ),
					),
					'result'     => array(
						'group' => 'payment_transaction_logs',
						'label' => __( 'Result', 'usces' ),
					),
				),
			),
		);
	}

	/**
	 * List of the member compared fields
	 *
	 * @since 2.7.4
	 * @access private
	 * @return array
	 */
	private function get_member_display_fields() {
		return array(
			'mem_email'           => array(
				'group' => 'other',
				'label' => __( 'e-mail', 'usces' ),
			),
			'mem_status'          => array(
				'group' => 'other',
				'label' => __( 'Rank', 'usces' ),
			),
			'mem_point'           => array(
				'group' => 'other',
				'label' => __( 'current point', 'usces' ),
			),
			'mem_name1'           => array(
				'group' => 'other',
				'label' => __( 'name', 'usces' ),
			),
			'mem_name2'           => array(
				'group' => 'other',
				'label' => __( 'name', 'usces' ),
			),
			'mem_name3'           => array(
				'group' => 'other',
				'label' => __( 'furigana', 'usces' ),
			),
			'mem_name4'           => array(
				'group' => 'other',
				'label' => __( 'furigana', 'usces' ),
			),
			'mem_zip'             => array(
				'group' => 'other',
				'label' => __( 'Zip/Postal Code	', 'usces' ),
			),
			'mem_pref'            => array(
				'group' => 'other',
				'label' => __( 'Province', 'usces' ),
			),
			'customer_country'    => array(
				'group' => 'other',
				'label' => __( 'Country', 'usces' ),
			),
			'mem_address1'        => array(
				'group' => 'other',
				'label' => __( 'city', 'usces' ),
			),
			'mem_address2'        => array(
				'group' => 'other',
				'label' => __( 'numbers', 'usces' ),
			),
			'mem_address3'        => array(
				'group' => 'other',
				'label' => __( 'building name', 'usces' ),
			),
			'mem_tel'             => array(
				'group' => 'other',
				'label' => __( 'Phone number', 'usces' ),
			),
			'mem_fax'             => array(
				'group' => 'other',
				'label' => __( 'FAX number', 'usces' ),
			),
			'custom_member'       => array(
				'group'            => 'custom_member',
				'label'            => __( 'Custom member field', 'usces' ),
				'is_repeater'      => true,
				'is_key_and_value' => true,
			),
			'custom_admin_member' => array(
				'group'            => 'custom_admin_member',
				'label'            => __( 'Admin custom field', 'usces' ),
				'is_repeater'      => true,
				'is_key_and_value' => true,
			),
		);
	}
}
