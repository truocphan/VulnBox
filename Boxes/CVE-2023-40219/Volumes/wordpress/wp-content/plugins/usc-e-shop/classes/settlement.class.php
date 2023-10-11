<?php
/**
 * Welcart クレジット決済設定
 *
 * @package  Welcart
 * @author   Collne Inc.
 * @since    2.5.0
 */
class usces_settlement {

	/**
	 * 利用できるクレジット決済モジュール
	 *
	 * @var array
	 */
	public $available_settlement;

	/**
	 * 利用中のクレジット決済モジュール
	 *
	 * @var array
	 */
	public $settlement_selected;

	/**
	 * Construct.
	 */
	public function __construct() {
		global $usces;

		$this->available_settlement = get_option( 'usces_available_settlement', array() );
		$this->settlement_selected  = get_option( 'usces_settlement_selected', array() );

		/**
		 * 初期設定
		 * (initial.php)
		 *
		 * $available_settlement = array(
		 * 'zeus'         => __( 'ZEUS Japanese Settlement', 'usces' ),
		 * 'remise'       => __( 'Remise Japanese Settlement', 'usces' ),
		 * 'jpayment'     => 'ROBOT PAYMENT',
		 * 'telecom'      => 'テレコムクレジット',
		 * 'digitalcheck' => 'メタップスペイメント',
		 * 'mizuho'       => 'みずほファクター',
		 * 'anotherlane'  => 'アナザーレーン',
		 * 'veritrans'    => 'ベリトランス Air-Web',
		 * 'paygent'      => 'ペイジェント',
		 *);
		 */
		if ( in_array( 'veritrans', $this->available_settlement, true ) ) {
			unset( $this->available_settlement['veritrans'] );
		}
		if ( ! in_array( 'epsilon', $this->available_settlement, true ) ) {
			$this->available_settlement['epsilon'] = 'イプシロン';
		}
		if ( ! in_array( 'paypal_cp', $this->available_settlement, true ) ) {
			$this->available_settlement['paypal_cp'] = __( 'PayPal Commerce Platform', 'usces' );
		}
		if ( ! in_array( 'sbps', $this->available_settlement, true ) ) {
			$this->available_settlement['sbps'] = 'SBペイメントサービス';
		}
		if ( ! in_array( 'dsk', $this->available_settlement, true ) ) {
			$this->available_settlement['dsk'] = 'DSK 電算システム';
		}
		if ( ! in_array( 'escott', $this->available_settlement, true ) ) {
			$this->available_settlement['escott'] = 'e-SCOTT Smart';
		}
		if ( ! in_array( 'welcart', $this->available_settlement, true ) ) {
			$settlement                 = array( 'welcart' => __( 'WelcartPay', 'usces' ) );
			$this->available_settlement = array_merge( $settlement, $this->available_settlement );
		}
		if ( ! in_array( 'paidy', $this->available_settlement, true ) ) {
			$this->available_settlement['paidy'] = 'Paidy';
		}
		update_option( 'usces_available_settlement', $this->available_settlement );

		if ( ! empty( $usces->payment_structure ) && is_array( $usces->payment_structure ) ) {
			if ( empty( $this->settlement_selected ) ) {
				$settlement_selected = array();
				$payment_structure   = array_keys( $usces->payment_structure );
				foreach ( (array) $payment_structure as $payment ) {
					if ( false !== strpos( $payment, 'acting_' ) ) {
						$acting = explode( '_', $payment );
						if ( isset( $acting[1] ) && 'paypal' === $acting[1] ) {
							$settlement_selected[] = $acting[1] . '_' . $acting[2];
						} else {
							$settlement_selected[] = apply_filters( 'usces_filter_acting_payment_slug', $acting[1], $acting );
						}
					}
				}
				$this->settlement_selected = array_unique( $settlement_selected );
				if ( 0 < count( $this->settlement_selected ) ) {
					update_option( 'usces_settlement_selected', $this->settlement_selected );
				}
			}
		}
	}

	/**
	 * クレジット決済モジュール選択
	 */
	public function setup() {
		$this->settlement_selected = get_option( 'usces_settlement_selected', array() );
		foreach ( (array) $this->settlement_selected as $settlement ) {
			if ( 'zeus' === $settlement ) {
				$zeus_settlement = ZEUS_SETTLEMENT::get_instance();
			} elseif ( 'escott' === $settlement ) {
				$escott_settle = ESCOTT_SETTLEMENT::get_instance();
			} elseif ( 'epsilon' === $settlement ) {
				$epsilon_settlement = new EPSILON_SETTLEMENT();
			} elseif ( 'welcart' === $settlement ) {
				$welcartpay_settlement = WELCARTPAY_SETTLEMENT::get_instance();
			} elseif ( 'remise' === $settlement ) {
				$remise_settlement = REMISE_SETTLEMENT::get_instance();
			} elseif ( 'sbps' === $settlement ) {
				$sbps_settlement = SBPS_SETTLEMENT::get_instance();
			} elseif ( 'dsk' === $settlement ) {
				$dsk_settlement = DSK_SETTLEMENT::get_instance();
			} elseif ( 'jpayment' === $settlement ) {
				$jpayment_settlement = JPAYMENT_SETTLEMENT::get_instance();
			} elseif ( 'telecom' === $settlement ) {
				$telecom_settlement = TELECOM_SETTLEMENT::get_instance();
			} elseif ( 'digitalcheck' === $settlement ) {
				$digitalcheck_settlement = DIGITALCHECK_SETTLEMENT::get_instance();
			} elseif ( 'mizuho' === $settlement ) {
				$mizuho_settlement = MIZUHO_SETTLEMENT::get_instance();
			} elseif ( 'anotherlane' === $settlement ) {
				$anotherlane_settlement = ANOTHERLANE_SETTLEMENT::get_instance();
			} elseif ( 'paygent' === $settlement ) {
				$paygent_settlement = PAYGENT_SETTLEMENT::get_instance();
			} elseif ( 'paypal_cp' === $settlement ) {
				$paypal_cp = PAYPAL_CP_SETTLEMENT::get_instance();
			} elseif ( 'paidy' === $settlement ) {
				$paidy = PAIDY_SETTLEMENT::get_instance();
			}
			// $yahoowallet_settle = new YAHOOWALLET_SETTLEMENT();
			// $paypal_ec = PAYPAL_EC_SETTLEMENT::get_instance();
			// $paypal_wpp = PAYPAL_WPP_SETTLEMENT::get_instance();
			// $veritrans_settlement = VERITRANS_SETTLEMENT::get_instance();
		}
		do_action( 'usces_action_settlement_setup' );
	}
}
