<?php

add_action('wp', function($wp) {
    if($wp->request === 'payment/paypal/web-hook') {
        \stmLms\Libraries\Paypal\WebHook::web_hook();
        die;
    }
});