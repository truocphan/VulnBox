<?php
/**
 * LWP_CUSTOM_Api class.
 *
 * The class names need to defined in format: LWP_%s_Api, where %s means Api name, e.g. LWP_Zenziva_Api
 * The class methods need to end with api key name
 *
 * @package Login with phone number
 */

/**
 * Class LWP_Zenziva_Api class.
 */
class LWP_CUSTOM_Api
{

    /**
     * Api Key
     *
     * @var string
     */
    public $url;
    public $header;
    public $body;
    public $method;
    public $smsText;

    /**
     * LWP_Handle_Messaging constructor.
     */
    public function __construct()
    {
        $options = get_option('idehweb_lwp_settings');
        if (!isset($options['idehweb_custom_api_header'])) $options['idehweb_custom_api_header'] = '';
        if (!isset($options['idehweb_custom_api_body'])) $options['idehweb_custom_api_body'] = '';
        if (!isset($options['idehweb_custom_api_url'])) $options['idehweb_custom_api_url'] = '';
        if (!isset($options['idehweb_custom_api_method'])) $options['idehweb_custom_api_method'] = 'GET';
        if (!isset($options['idehweb_custom_api_smstext'])) $options['idehweb_custom_api_smstext'] = '';
        $this->header = $options['idehweb_custom_api_header'];
        $this->body = $options['idehweb_custom_api_body'];
        $this->url = $options['idehweb_custom_api_url'];
        $this->method = $options['idehweb_custom_api_method'];
        $this->smstext = $options['idehweb_custom_api_smstext'];
    }

    public function lwp_replace_strings($string, $phone, $code, $message = '')
    {
        $string = str_replace('${phone_number}', $phone, $string);
        $string = str_replace('${code}', $code, $string);
        $string = str_replace('${message}', $message, $string);

//        $string = str_replace('${text}', $text, $string);
        return $string;
    }

    public function lwp_send_sms($phone, $code)
    {
        $method = $this->method;
        $this->smstext = $this->lwp_replace_strings($this->smstext, $phone, $code);

        $url = $this->lwp_replace_strings($this->url, $phone, $code, $this->smstext);
        $this->header = $this->lwp_replace_strings($this->header, $phone, $code, $this->smstext);
        $this->body = $this->lwp_replace_strings($this->body, $phone, $code, $this->smstext);
        $header = json_decode($this->header, true);
        $body = json_decode($this->body, true);


        if ($method == 'GET') {
            $response = wp_safe_remote_get(
                $url,
                array(
                    'method' => 'GET',
                    'timeout' => 60,
                    'redirection' => 5,
                    'blocking' => true,
                    'headers' => $header,
//                    'body' => $body,
                    'cookies' => array(),
                )
            );
        }
        if ($method == 'POST') {
//            print_r($method);
//            print_r($url);
//            print_r($body);
//            print_r($header);
//            print_r($header);
//            die();
            $response = wp_safe_remote_post(
                $url,
                array(
                    'method' => 'POST',
                    'timeout' => 60,
                    'redirection' => 5,
                    'blocking' => true,
                    'headers' => $header,
                    'body' => json_encode($body),
                    'cookies' => array(),
                )
            );

        }

        $user_message = '';
        $dev_message = array();
        $res_param = array();

        if (is_wp_error($response)) {
//            print_r("*******");
            $dev_message = $response->get_error_message();
            $success = false;
        } else {
//            print_r("yes");
//            print_r("\n");
//            print_r($response['body']);

            $decoded_response = (array)json_decode($response['body']);
            $res_success = isset($decoded_response['success']) ? $decoded_response['success'] : false;
            $error_code = isset($decoded_response['error_code']) ? $decoded_response['error_code'] : '';

            if ($res_success) {
                $success = true;
            } elseif ('60033' === $error_code) {
                $success = false;
                $user_message = __('Phone number is invalid', 'orion-login');
            } elseif ('60001' === $error_code) {
                $success = false;
                $user_message = __('Invalid API key', 'orion-login');
            } elseif ('60082' === $error_code) {
                $success = false;
                $user_message = __('Cannot send SMS to landline phone numbers', 'orion-login');
            } else {
                $success = false;
                $user_message = __('Api error', 'orion-login');
            }
        }

        return(array(
            'success' => $success,
            'userMessage' => $user_message,
            'devMessage' => $dev_message,
            'resParam' => $res_param,
        ));
//        die();
    }


}
