<?php

namespace myWagepay\Baas\Request;

use Exception;
use Illuminate\Support\Facades\Log;
use myWagepay\Baas\Request\WageBase;
use Illuminate\Support\Facades\Cache;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;


/**
 * Class Authenticator
 *
 */
class AuthBroker
{

    /**
     * @var string
     */
    protected $endpoint;
    /**
     * @var WageBase
     */
    protected $wageBase;
    /**
     * @var AuthBroker
     */
    protected static $instance;
    /**
     * @var bool
     */
    /**
     * @var string
     */
    private $credentials;

    /**
     * Authenticator constructor.
     *
     * @param  WageBase $wageBase
     */
    public function __construct(WageBase $wageBase)
    {
        $this->wageBase = $wageBase;
        self::$instance = $this;
    }

    /**
     * @param bool $bulk
     * @return string
     */
    public function authenticate()
    {
        $this->generateCredentials();
        if (!empty($key = $this->getToken())) {
            return $key;
        } else {
            try {
                $response = $this->AuthRequest();
                if ($response->getStatusCode() === 200) {
                    $body = json_decode($response->getBody());
                    $this->saveToken($body);
                    return $body->data->token;
                }
                throw new Exception($response->getReasonPhrase());
            } catch (RequestException $exception) {
                Log::alert('ATHBUG  ' . $exception->getResponse()->getReasonPhrase());
                $message = $exception->getResponse() ? $exception->getResponse()->getReasonPhrase() : $exception->getMessage();
                throw $this->generateException($message);
            }
        }
    }

    /**
     * @param $reason
     */
    private function generateException($reason)
    {
        switch (strtolower($reason)) {
            case 'bad request: invalid credentials':
                return new Exception('Invalid consumer key and secret combination');
            default:
                return new Exception($reason);
        }
    }

    /**
     * @return $this
     */
    private function generateCredentials()
    {
        $this->credentials = base64_encode(config('mywagepay.consumer_key') . ':' . config('mywagepay.consumer_secret'));
        return $this;
    }

    /**
     * @return ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function AuthRequest()
    {
        return $this->wageBase->client->request(
            'GET',
            $this->wageBase->baseUrl . 'auth/pub3api/login',
            [
                'headers' => [
                    'Authorization' => 'Basic ' . $this->credentials,
                ],
            ]
        );
    }

    /**
     * @return mixed
     */
    private function getToken()
    {
        return Cache::get($this->credentials);
    }

    /**
     * Store the credentials in the cache.
     *
     * @param $credentials
     */
    private function saveToken($tokenBody)
    {

        Cache::put($this->credentials, $tokenBody->data->token, 30);
    }
}
