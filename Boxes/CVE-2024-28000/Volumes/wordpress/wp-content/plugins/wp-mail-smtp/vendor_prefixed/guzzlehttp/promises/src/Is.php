<?php

declare (strict_types=1);
namespace WPMailSMTP\Vendor\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     */
    public static function pending(\WPMailSMTP\Vendor\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() === \WPMailSMTP\Vendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     */
    public static function settled(\WPMailSMTP\Vendor\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() !== \WPMailSMTP\Vendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     */
    public static function fulfilled(\WPMailSMTP\Vendor\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() === \WPMailSMTP\Vendor\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     */
    public static function rejected(\WPMailSMTP\Vendor\GuzzleHttp\Promise\PromiseInterface $promise) : bool
    {
        return $promise->getState() === \WPMailSMTP\Vendor\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
