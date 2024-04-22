<?php

namespace stmLms\Libraries\Paypal;

use stmLms\Libraries\Paypal\PayPal;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Core\PayPalHttpConnection;

class Payout {

	public $paypal;
	public $payout;

	/**
	 * Payout constructor.
	 *
	 * @param \stmLms\Libraries\Paypal\PayPal $paypals
	 */
	public function __construct(PayPal $paypal) {
		$this->paypal = $paypal;
		$this->payout = new \PayPal\Api\Payout();
	}

	/**
	 * @param $items
	 *
	 * @return array
	 */
	public function create_batch_payout($items) {
		$result = array(
			'output' => [],
			'success' => true,
			'message' => "Success",
		);

		if(empty($items)){
			$result['message'] = "Payout not found";
			return $result;
		}

		$senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
		$senderBatchHeader->setSenderBatchId(uniqid()."_".time())
		                  ->setEmailSubject("You have a payment");
		$this->payout->setSenderBatchHeader($senderBatchHeader);
		foreach ($items  as $item){
			$email = get_user_meta($item['author_payout'], "stm_lms_paypal_email");
			if(isset($email[0])){
				$this->payout->addItem(
					new \PayPal\Api\PayoutItem(
						array(
							"recipient_type" => "EMAIL",
							"receiver" => $email[0],
							"note" => "Thank you.",
							"sender_item_id" => $item["id"],
							"amount" => array(
								"value" => $item["fee_amounts"],
								"currency" => $this->paypal->currency
							)
						)
					)
				);
			}
		}
		try {
			$result['output'] = $this->payout->create(null, $this->paypal->apiContext);
			$result['message'] = "Success";
		} catch (Exception $ex) {
			$result['success'] = false;
			$result['message'] = $ex->getMessage();
			return $result;
		}catch (PayPalConnectionException $ex) {
			$ex = $ex->getData();
			if(!empty(trim($ex)))
				$ex = json_decode($ex, true);

			$result['success'] = false;
			$result['message'] = "Payout Error :(";

			if(isset($ex["message"]))
				$result['message'] = $ex["message"];

			if(isset($ex["error_description"]))
				$result['message'] = $ex["error_description"];

			return $result;
		}
		return $result;
	}
}