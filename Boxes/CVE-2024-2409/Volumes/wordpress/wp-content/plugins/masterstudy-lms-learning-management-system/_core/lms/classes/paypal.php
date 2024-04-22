<?php

class STM_LMS_PayPal {

    private $url;
    public $currency_code;
    public $email;
    public $return_url;

    function __construct($amount = 10, $invoice = 0, $item_name = '', $item_number = '', $user_email = '', $return_url = '')
    {

        $payments = STM_LMS_Options::get_option('payment_methods');

        if(!empty($payments['paypal']) and !empty($payments['paypal']['enabled']) and !empty($payments['paypal']['fields'])) {
            $paypal = $payments['paypal'];
            $paypal_fields = $paypal['fields'];

            $this->url = ($paypal_fields['paypal_mode'] == 'live') ? 'www.paypal.com' : 'www.sandbox.paypal.com';
            $this->currency_code = $paypal_fields['currency_code'];
            $this->email = $paypal_email = $paypal_fields['paypal_email'];
            $this->return_url = empty($return_url) ? add_query_arg('paypal_order_id', $invoice, home_url()) : $return_url;
            $this->invoice = $invoice;
            $this->amount = $amount;
            $this->item_name = $item_name;
            $this->item_number = $item_number;
            $this->user_email = $user_email;

            $this->return_url = apply_filters('stm_paypal_return_url', $this->return_url);
        }
    }

    function generate_payment_url() {
        $get_params = array(
            'cmd' => '_xclick',
            'business' => $this->email,
            'no_shipping' => 1,
            'no_note' => 1,
            'currency_code' => $this->currency_code,
            'bn' => 'PP%2dBuyNowBF',
            'charset' => 'UTF%2d8',
            'item_name' => $this->item_name,
            'item_number' => $this->item_number,
            'invoice' => $this->invoice,
            'return' => $this->return_url,
            'email' => $this->user_email,
            'rm' => 2,
            'amount' => $this->amount,
            'notify_url' => add_query_arg('stm_lms_check_ipn', 1, home_url())
        );

        $url = 'https://' . $this->url . '/cgi-bin/webscr?' . http_build_query($get_params);


        return $url;
    }

    function check_payment($data = array()) {

        $order_id = $data['invoice'];


        $req = 'cmd=_notify-validate';

        foreach ($data as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }

        $ch = curl_init('https://' . $this->url . '/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

        if (!($res = curl_exec($ch))) {
            echo("Got " . curl_error($ch) . " when processing IPN data");
            curl_close($ch);
            return false;
        }
        curl_close($ch);

        $user_id = get_post_meta($order_id, 'user_id', true);
        $previous_status = get_post_meta($order_id, 'status', true);

        if (strcmp($res, "VERIFIED") == 0) {

            if($previous_status !== 'completed') {
                update_post_meta($order_id, 'status', 'completed');
                STM_LMS_Order::accept_order($user_id, $order_id);
            }
        }
    }
}

if (!empty($_GET['stm_lms_check_ipn'])) {
    $paypal = new STM_LMS_PayPal();
    $paypal->check_payment($_REQUEST);
    header('HTTP/1.1 200 OK');
    exit;
}