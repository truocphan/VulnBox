<?php
namespace stmLms\Libraries\Paypal;

use stmLms\Classes\Models\StmLmsPayout;
use stmLms\Libraries\Paypal\PayPal;
use Psr\Log\InvalidArgumentException;
use PayPal\Api\VerifyWebhookSignature;
use PayPal\Exception\PayPalConnectionException;

class WebHook {

	/**
	 * @param $data
	 *
	 * @return array
	 */
	public static function verify_webhook($data) {
		$paypal_data = PayPal::getData();
		$apiContext  = PayPal::getApiContext();
		$headers     = getallheaders();
		$headers     = array_change_key_case($headers, CASE_UPPER);
		$result      = array(
			'success' => false,
			'status'  => null, // SUCCESS, FAILURE
			'message' => "",
		);

		if(!isset($headers['PAYPAL-AUTH-ALGO']) OR
		   !isset($headers['PAYPAL-TRANSMISSION-ID']) OR
		   !isset($headers['PAYPAL-CERT-URL']) OR
		   !isset($headers['PAYPAL-TRANSMISSION-SIG']) OR
		   !isset($headers['PAYPAL-TRANSMISSION-TIME'])
		) return $result;

		$signatureVerification = new VerifyWebhookSignature();
		$signatureVerification->setAuthAlgo($headers['PAYPAL-AUTH-ALGO']);
		$signatureVerification->setTransmissionId($headers['PAYPAL-TRANSMISSION-ID']);
		$signatureVerification->setCertUrl($headers['PAYPAL-CERT-URL']);
		$signatureVerification->setWebhookId($paypal_data['webhook_id']);
		$signatureVerification->setTransmissionSig($headers['PAYPAL-TRANSMISSION-SIG']);
		$signatureVerification->setTransmissionTime($headers['PAYPAL-TRANSMISSION-TIME']);
		$signatureVerification->setRequestBody($data);

		try {
			$output = $signatureVerification->post($apiContext);
		} catch (Exception $ex) {
			$result['message']   = $ex->getMessage();
			$result['errorData'] = $ex->getData();
			return $result;
		}catch (PayPalConnectionException $ex) {
			$result['message']   = $ex->getMessage();
			$result['errorData'] = $ex->getData();
			return $result;
		} catch (InvalidArgumentException $ex) {
			$result['message']   = $ex->getMessage();
			$result['errorData'] = $ex->getData();
			return $result;
		}

		$status = $output->getVerificationStatus();
		if($status == "SUCCESS") {
			$result['status'] = true;
			$result['success'] = true;
		}
		return $result;
	}


	public static function web_hook() {

		if( empty($data = file_get_contents('php://input')) ){
			http_response_code(400);
			exit();
		}

		$paypal_data = PayPal::getData();

		// Verify webhook data
		if(isset($paypal_data['verifying_webhooks']) AND $paypal_data['verifying_webhooks']){
			$verify = self::verify_webhook($data);
			if(!$verify['success']){
				http_response_code(400);
				exit();
			}
		}

		$data = json_decode($data, true);
		if(isset($data['resource_type']) AND $data['resource_type'] == "payouts_item" AND isset($data['resource']) AND isset($data['resource']['payout_item'])){

			$payout = StmLmsPayout::query()
			                      ->where("ID", $data['resource']['payout_item']['sender_item_id'])
			                      ->where("post_type", "stm-payout")
			                      ->findOne();
			if($payout){
				$fee_amounts = get_post_meta($payout->ID, "fee_amounts", true);
				if( $fee_amounts == $data['resource']['payout_item']['amount']['value']){

					if($data['resource']['transaction_status'] == "SUCCESS"){
						update_post_meta($payout->ID, "status", $data['resource']['transaction_status']);
						update_post_meta($payout->ID, "transaction_id", $data['resource']['transaction_id']);
						update_post_meta($payout->ID, "paid", 1);
						$payout->payout_items_set_success();
					}

					if($data['resource']['transaction_status'] != "SUCCESS"){
						update_post_meta($payout->ID, "status", $data['resource']['transaction_status']);
					}
				}
			}
		}

		http_response_code(200);
		exit();
	}

	public static function getWebHookUrl() {
		return get_site_url(null, 'payment/paypal/web-hook');
	}
}










