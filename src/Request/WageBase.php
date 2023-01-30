<?php

namespace myWagepay\Baas\Request;

use GuzzleHttp\ClientInterface;

/**
 * Class WageBase
 *
 */
class WageBase
{
    /**
     * @var ClientInterface
     */
    public $client;
    /**
     * @var Authenticator
     */
    public $auth;

    public $baseUrl = 'https://api.mywagepay.com/api/v1/';

    /**
     * WageBase constructor.
     *
     * @param  ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->auth = new AuthBroker($this);
    }
}
