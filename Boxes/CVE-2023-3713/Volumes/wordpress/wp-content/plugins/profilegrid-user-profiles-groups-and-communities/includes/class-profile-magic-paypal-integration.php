<?php
/**
 * PHP Paypal IPN Integration Class
 * 6.25.2008 - Eric Wang, http://code.google.com/p/paypal-ipn-class-php/
 *
 * This file provides neat and simple method to validate the paid result with Paypal IPN.
 * It's NOT intended to make the paypal integration "plug 'n' play".
 * It still requires the developer to understand the paypal process and know the variables
 * you want/need to pass to paypal to achieve what you want.
 *
 * @author      Eric Wang <eric.wzy@gmail.com>
 * @copyright  (C) 2008 - 2009 Eric.Wang
 *
 */
/** filename of the IPN log */
define( 'PM_LOG_FILE', 'ipn_results.log' );
define( 'PM_SSL_P_URL', 'https://www.paypal.com/cgi-bin/webscr' );
define( 'PM_SSL_SAND_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr' );
class profile_magic_paypal_class {

	private $ipn_status;                // holds the last status
	public $admin_mail;                 // receive the ipn status report pre transaction
	public $paypal_mail;
	public $from_mail;              // paypal account, if set, class need to verify receiver
	public $txn_id;                     // array: if the txn_id array existed, class need to verified the txn_id duplicate
	public $ipn_log;                    // bool: log IPN results to text file?
	private $ipn_response;              // holds the IPN response from paypal
	public $ipn_data = array();         // array contains the POST values for IPN
	private $fields  = array();          // array holds the fields to submit to paypal
	private $ipn_debug;                 // ipn_debug
	private $send_box;

	// initialization constructor.  Called when class is created.
	function __construct() {
		$this->ipn_status   = '';
		$this->admin_mail   = null;
		$this->from_mail    = null;
		$this->paypal_mail  = null;
		$this->txn_id       = null;
		$this->tax          = null;
		$this->ipn_log      = true;
		$this->ipn_response = '';
		$this->ipn_debug    = false;
		$this->sendbox      = 'no';
	}
	// adds a key=>value pair to the fields array, which is what will be
	// sent to paypal as POST variables.
	public function add_field( $field, $value ) {
		$this->fields[ "$field" ] = $value;
	}
	// this function actually generates an entire HTML page consisting of
	// a form with hidden elements which is submitted to paypal via the
	// BODY element's onLoad attribute.  We do this so that you can validate
	// any POST vars from you custom form before submitting to paypal.  So
	// basically, you'll have your own form which is submitted to your script
	// to validate the data, which in turn calls this function to create
	// another hidden form and submit to paypal.

	// The user will briefly see a message on the screen that reads:
	// "Please wait, your order is being processed..." and then immediately
	// is redirected to paypal.
	public function submit_paypal_post() {
		$paypal_url = ( $this->sendbox == '1' ) ? PM_SSL_SAND_URL : PM_SSL_P_URL;
                ob_start();
		echo "<html>\n";
		echo '<head><title>' . esc_html__( 'Processing Payment...', 'profilegrid-user-profiles-groups-and-communities' ) . "</title></head>\n";
		echo "<body onLoad=\"document.forms['paypal_form'].submit();\">\n";
		echo '<center><h2>' . esc_html__( 'Please wait, your order is being processed and you will be redirected to the paypal website.', 'profilegrid-user-profiles-groups-and-communities' ) . "</h2></center>\n";
		echo '<form method="post" name="paypal_form" ';
		echo 'action="' . esc_url( $paypal_url ) . "\">\n";
		if ( isset( $this->paypal_mail ) ) {
			echo '<input type="hidden" name="business" value="' . esc_attr( $this->paypal_mail ) . "\"/>\n";
        }
		foreach ( $this->fields as $name => $value ) {
			echo '<input type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . "\"/>\n";
		}
		echo '<center><br/><br/>' . esc_html__( 'If you are not automatically redirected to paypal within 5 seconds...', 'profilegrid-user-profiles-groups-and-communities' ) . "<br/><br/>\n";
		echo '<input type="submit" value="' . esc_attr__( 'Click Here', 'profilegrid-user-profiles-groups-and-communities' ) . '"></center>';
		echo "\n";
		echo "</form>\n";
		echo "</body></html>\n";
                return ob_get_contents();
	}

	/**
	 * validate the IPN
	 *
	 * @return bool IPN validation result
	 */
	public function validate_ipn() {
                $pm_sanitizer = new PM_sanitizer;
                $post_obj = $pm_sanitizer->sanitize($_POST);
                $hostname = gethostbyaddr( filter_var( $_SERVER ['REMOTE_ADDR'], FILTER_VALIDATE_IP ) );
		if ( ! preg_match( '/paypal\.com$/', $hostname ) ) {
			$this->ipn_status =  esc_html__( 'Validation post isn\'t from PayPal', 'profilegrid-user-profiles-groups-and-communities' );
			$this->log_ipn_results( false );
			return false;
		}

		if ( isset( $this->paypal_mail ) && strtolower( sanitize_email( $post_obj['receiver_email'] ) ) != strtolower( trim( $this->paypal_mail ) ) ) {
			$this->ipn_status =  esc_html__( 'Receiver Email Not Match', 'profilegrid-user-profiles-groups-and-communities' );
			$this->log_ipn_results( false );
			return false;
		}

		if ( isset( $this->txn_id )&& in_array( sanitize_text_field( $post_obj['txn_id'] ), $this->txn_id ) ) {
			$this->ipn_status =  esc_html__( 'txn_id have a duplicate', 'profilegrid-user-profiles-groups-and-communities' );
			$this->log_ipn_results( false );
			return false;
		}
		// parse the paypal URL
		$paypal_url = ( $post_obj['test_ipn'] == 1 ) ? PM_SSL_SAND_URL : PM_SSL_P_URL;
		$url_parsed = wp_parse_url( $paypal_url );

		// generate the post string from the _POST vars aswell as load the
		// _POST vars into an arry so we can play with them from the calling
		// script.
		$post_string = '';
		foreach ( $post_obj as $field=>$value ) {
			$this->ipn_data[ "$field" ] = $value;
			$post_string               .= $field . '=' . rawurlencode( sanitize_text_field( $value ) ) . '&';
		}
		$post_string .='cmd=_notify-validate'; // append ipn command

		// open the connection to paypal
		if ( isset( $post_obj['test_ipn'] ) ) {
			$fp = fsockopen( 'ssl://www.sandbox.paypal.com', '443', $err_num, $err_str, 60 );
		} else {
			$fp = fsockopen( 'ssl://www.paypal.com', '443', $err_num, $err_str, 60 );
        }

		if ( !$fp ) {
			// could not open the connection.  If logging is on, the error message
			// will be in the log.
			$this->ipn_status = "fsockopen error no. $err_num: $err_str";
			$this->log_ipn_results( false );
			return false;
		} else {
			// Post the data back to paypal
			fputs( $fp, "POST $url_parsed[path] HTTP/1.1\r\n" );
			fputs( $fp, "Host: $url_parsed[host]\r\n" );
			fputs( $fp, "Content-type: application/x-www-form-urlencoded\r\n" );
			fputs( $fp, 'Content-length: ' . strlen( $post_string ) . "\r\n" );
			fputs( $fp, "Connection: close\r\n\r\n" );
			fputs( $fp, $post_string . "\r\n\r\n" );

			// loop through the response from the server and append to variable
			while ( !feof( $fp ) ) {
				$this->ipn_response .= fgets( $fp, 1024 );
			}
			fclose( $fp ); // close connection
		}

		// Invalid IPN transaction.  Check the $ipn_status and log for details.
		if ( ! preg_match( '/VERIFIED/i', $this->ipn_response ) ) {
			$this->ipn_status =  esc_html__( 'IPN Validation Failed', 'profilegrid-user-profiles-groups-and-communities' );
			$this->log_ipn_results( false );
			return false;
		} else {
			$this->ipn_status =  esc_html__( 'IPN VERIFIED', 'profilegrid-user-profiles-groups-and-communities' );
			$this->log_ipn_results( true );
			return true;
		}
	}

	private function log_ipn_results( $success ) {
		$hostname = gethostbyaddr( filter_var( $_SERVER ['REMOTE_ADDR'], FILTER_VALIDATE_IP ) );
		// Timestamp
		$text = '[' . gmdate( 'm/d/Y g:i A' ) . '] - ';
		// Success or failure being logged?
		if ( $success ) {
			$this->ipn_status = $text . 'SUCCESS:' . $this->ipn_status . "\r\n";
		} else {
			$this->ipn_status = $text . 'FAIL: ' . $this->ipn_status . "\r\n";
        }
			// Log the POST variables
		$this->ipn_status .= '[From:' . $hostname . '|' . filter_var( $_SERVER ['REMOTE_ADDR'], FILTER_VALIDATE_IP ) . "]IPN POST Vars Received By Paypal_IPN Response API:\r\n";
		foreach ( $this->ipn_data as $key => $value ) {
			$this->ipn_status .= "<p>$key=$value </p>\r\n";
		}
		// Log the response from the paypal server
		$this->ipn_status .=  esc_html__( 'IPN Response from Paypal Server:', 'profilegrid-user-profiles-groups-and-communities' ) . '\r\n' . $this->ipn_response;
		$this->write_to_log();
	}

	private function write_to_log() {
		if ( ! $this->ipn_log ) {
			return; // is logging turned off?
        }

		if ( file_exists( PM_LOG_FILE ) ) {
			unlink( PM_LOG_FILE );
		}
                    return;

                // Write to log
		$fp = fopen( PM_LOG_FILE, 'w' );
		fwrite( $fp, '' );
		fclose( $fp ); // close file
		chmod( PM_LOG_FILE, 0600 );
	}
	public function send_report( $subject ) {
		$headers  = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8\r\n";
		$headers .= 'From:' . $this->from_mail . "\r\n";
		$body    .=  esc_html__( 'from ', 'profilegrid-user-profiles-groups-and-communities' ) . esc_html( $this->ipn_data ['payer_email'] ) . esc_html__( ' on ', 'profilegrid-user-profiles-groups-and-communities' ) . gmdate( 'm/d/Y' );
		$body    .=  esc_html__( ' at ', 'profilegrid-user-profiles-groups-and-communities' ) . gmdate( 'g:i A' ) . "\n\n" . esc_html__( 'Details:', 'profilegrid-user-profiles-groups-and-communities' ) . "\n" . $this->ipn_status;

		wp_mail( $this->admin_mail, $subject, $body, $headers );
	}
	public function print_report() {
		$find []      = "\n";
		$replace []   = '<br/>';
		$html_content = str_replace( $find, $replace, $this->ipn_status );
		echo wp_kses_post( $html_content );
	}

	public function dump_fields() {

	}
	private function debug( $msg ) {
		if ( ! $this->ipn_debug ) {
			return;
        }

		$today  =  gmdate( 'Y-m-d H:i:s ' );
		$myFile = 'ipn_debugs.log';

		if ( file_exists( $myFile ) ) {
			@unlink( $myFile );
		}
                return;

		$fh = fopen( $myFile, 'w' ) or die( esc_html__( "Can't open debug file. Please manually create the 'debug.log' file and make it writable.", 'profilegrid-user-profiles-groups-and-communities' ) );

		fwrite( $fh, '' );
		fclose( $fh );
		chmod( $myFile, 0600 );

	}
}

