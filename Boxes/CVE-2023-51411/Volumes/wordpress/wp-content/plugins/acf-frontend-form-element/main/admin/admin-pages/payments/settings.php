<?php 
namespace Frontend_Admin;

use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
 
class FEA_Payments_Settings{

    public function set_credit_card( $payment_card ){
        $current_user = wp_get_current_user();
        $ip_address = $_SERVER['REMOTE_ADDR'];
        if( $ip_address == '::1' ) $ip_address = '8.8.8.8';
        $ip_json = file_get_contents("https://www.iplocate.io/api/lookup/$ip_address");
        $ip_data = json_decode($ip_json, true);

        $full_name = explode( ' ', $payment_card['name'], 2 );
        $first_name = $full_name[0];
        $last_name = isset($full_name[1]) ? $full_name[1] : '';
        $expire_date = explode( '/', $payment_card['expiry'] );
        $customer_email = isset( $current_user->user_email ) ? $current_user->user_email : '';
        $billingAddress1 = isset( $payment_card['billingAddress1'] ) ? $payment_card['billingAddress1'] : 0;
        $billingAddress2 = isset( $payment_card['billingAddress2'] ) ? $payment_card['billingAddress2'] : 0;
        $billingCity = isset( $payment_card['billingCity'] ) ? $payment_card['billingCity'] : 0;
        $billingPostcode = isset( $payment_card['billingPostCode'] ) ? $payment_card['billingPostcode'] : 0;
        $billingState = isset( $payment_card['billingState'] ) ? $payment_card['billingState'] : 0;
        $billingCountry = isset( $payment_card['billingCountry'] ) ? $payment_card['billingCountry'] : $ip_data['country_code'];
        
        try {
            $card = new CreditCard( [
                'name' => $payment_card['name'],
                'number' => $payment_card['number'],
                'cvv' => $payment_card['cvv'],
                'expiryMonth' => $expire_date[0],
                'expiryYear' => $expire_date[1],
                'email' => $customer_email,
                'billingAddress1' => $billingAddress1,
                'billingAddress2' => $billingAddress2,
                'billingCountry' => $billingCountry,
                'billingCity'=> $billingCity,
                'billingPostcode' => $billingPostcode,
                'billingState' => $billingState,
            ] );
            $card->validate();
            
            return $card;
        }
        
        //catch exception
        catch( \Exception $e) {
            echo $e->getMessage();
			wp_die();
        }      
    }  

	public function subtract_add_submission( $new_status, $old_status, $post ){
		$post_id = $post->ID;
	
		if ( $old_status == $new_status ) return;

		$post_author = get_post_field( 'post_author', $post->ID );
		$submitted = get_user_meta( $post_author, 'acff_payed_submitted', true );

		if( ( $old_status == 'publish' || $old_status == 'pending' ) && ( $new_status != 'publish' && $new_status != 'pending' ) ){
			$submitted--;
			update_user_meta( $post_author, 'acff_payed_submitted', $submitted );
		}
		if( ( $old_status != 'publish' && $old_status != 'pending' ) && ( $new_status == 'publish' || $new_status == 'pending' ) ){
			if( ! $submitted ){
				$submitted = 1;
			}else{
				$submitted++;
			}
			update_user_meta( $post_author, $post_form . 'acff_payed_submitted', $submitted );
		}
		update_post_meta( $post_id, 'acff_payed_post', $post_author );

	}
	public function subtract_submission( $post_id ){
        $paying_user = get_post_meta( $post_id, 'acff_payed_post', true );

        if( ! $paying_user ) return;
		
		$submitted = get_user_meta( $active_user_id, 'acff_payed_submitted', true );
		$submitted--;
        update_user_meta( $active_user_id, 'acff_payed_submitted', $submitted );
        
    }

    public function payment_settings_fields( $field_keys ){
		$stripe_active = array(
			'field' => 'acff_stripe_active',
			'operator' => '==',
			'value' => '1',
		);
		$paypal_active = array(
			'field' => 'acff_paypal_active',
			'operator' => '==',
			'value' => '1',
		);
		       
        $local_fields = array(
			'acff_stripe_tab' => array(
				'label' => __( 'Stripe', 'acf-frontend-form-element' ),
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			'acff_stripe_active' => array(
				'label' => __( 'Activate Stripe', 'acf-frontend-form-element' ),
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			'acff_stripe_message' => array(
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						$stripe_active,
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => __( '<h2>Set Up</h2>

Click <a target="_blank" href="https://dashboard.stripe.com/register">here</a> to create a Stripe account. Once you do that you will recieve your API Keys.', 'acf-frontend-form-element' ),
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
			'acff_stripe_live_mode' => array(
				'label' => __( 'Use Live Keys', 'acf-frontend-form-element' ),
				'type' => 'true_false',
				'instructions' => __( 'We reccomend testing out the test keys before using the live keys', 'acf-frontend-form-element' ),
				'required' => 0,
				'conditional_logic' => array(
					array(
						$stripe_active,
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'only_front' => 0,
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			'acff_stripe_live_publish_key' => array(
				'label' => __( 'Live Publishable Key', 'acf-frontend-form-element' ),
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						$stripe_active,
						array(
							'field' => 'acff_stripe_live_mode',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '50.1',
					'class' => '',
					'id' => '',
				),
			),
			'acff_stripe_live_secret_key' => array(
				'label' => __( 'Live Secret Key', 'acf-frontend-form-element' ),
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						$stripe_active,
						array(
							'field' => 'acff_stripe_live_mode',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '50.1',
					'class' => '',
					'id' => '',
				),
			),
			'acff_stripe_test_publish_key' => array(
				'label' => __( 'Test Publishable Key', 'acf-frontend-form-element' ),
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						$stripe_active,
						array(
							'field' => 'acff_stripe_live_mode',
							'operator' => '!=',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '50.1',
					'class' => '',
					'id' => '',
				),
			),
			'acff_stripe_test_secret_key' => array(
				'label' => __( 'Test Secret Key', 'acf-frontend-form-element' ),
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						$stripe_active,
						array(
							'field' => 'acff_stripe_live_mode',
							'operator' => '!=',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '50.1',
					'class' => '',
					'id' => '',
				),
			),			
			'acff_paypal_tab' => array(
				'label' => __( 'Paypal', 'acf-frontend-form-element' ),
				'type' => 'tab',
				'instructions' => '',
				'required' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'placement' => 'top',
				'endpoint' => 0,
			),
			 'acff_paypal_active' => array(
				'label' => __( 'Activate Paypal', 'acf-frontend-form-element' ),
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
				'custom_sold_ind' => 0,
			),
			'acff_paypal_message' => array(
				'type' => 'message',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						$paypal_active,
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => __( '<h2>Set Up</h2>

Click <a target="_blank" href="https://developer.paypal.com/developer/applications/create">here</a> to create a PayPal App. Once you do that you will recieve your API Keys.', 'acf-frontend-form-element' ),
				'new_lines' => 'wpautop',
				'esc_html' => 0,
			),
			'acff_paypal_live_mode' => array(
				'label' => __( 'Live Mode', 'acf-frontend-form-element' ),
				'type' => 'true_false',
				'instructions' => __( 'We reccomend trying out in test mode before switching to live mode', 'acf-frontend-form-element' ),
				'required' => 0,
				'conditional_logic' => array(
					array(
						$paypal_active,
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'ui' => 1,
			),
			'acff_paypal_client_id' => array(
				'label' => __( 'Client ID', 'acf-frontend-form-element' ),
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						$paypal_active,
					),
				),
				'wrapper' => array(
					'width' => '50.1',
					'class' => '',
					'id' => '',
				),
			),
			'acff_paypal_secret' => array(
				'label' => __( 'Secret', 'acf-frontend-form-element' ),
				'type' => 'text',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => array(
					array(
						$paypal_active,
					),
				),
				'wrapper' => array(
					'width' => '50.1',
					'class' => '',
					'id' => '',
				),
			),
		);
		return $local_fields;
	}

	public function initialize_gateways(){
		if( get_option( 'acff_stripe_active' ) ){
			fea_instance()->stripe = Omnipay::create('Stripe');
			$stripe_secret_key = ( get_option( 'acff_stripe_live_mode' ) ) ? get_option( 'acff_stripe_live_secret_key' ) : get_option( 'acff_stripe_test_secret_key' );

			if( $stripe_secret_key ){
				fea_instance()->stripe->setApiKey( $stripe_secret_key );
			}else{
				fea_instance()->stripe = false;
			}
		}
		
		if( get_option( 'acff_paypal_active' ) ){
			fea_instance()->paypal = Omnipay::create('PayPal_Rest');
			$paypal_client = get_option( 'acff_paypal_client_id' );
			$paypal_secret = get_option( 'acff_paypal_secret' );
			if( $paypal_client && $paypal_secret ){
				fea_instance()->paypal->initialize(array(
					'clientId' => $paypal_client,
					'secret'   => $paypal_secret,
					'testMode' => ! get_option( 'acff_paypal_live_mode' ),
				));
			}else{
				fea_instance()->paypal = false;
			}
		}
	}


    public function __construct() {
		require_once( __DIR__ . '/crud.php');
		require_once( __DIR__ . '/currencies.php');
		require_once( FEAP_DIR . '/includes/vendor/autoload.php' );

		$this->initialize_gateways();
		
		//add_action( 'transition_post_status' , [ $this, 'subtract_add_submission'], 10, 3 );	
        //add_action( 'delete_post' , [ $this, 'acff_subtract_submission'] );
        add_filter( 'frontend_admin/payments_fields', [ $this, 'payment_settings_fields'] );

	}
}
new FEA_Payments_Settings( $this );