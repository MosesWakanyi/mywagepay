<?php

namespace myWagepay\Baas\Request;

use Exception;
use Illuminate\Support\Facades\Log;
use myWagepay\Baas\Request\WageBase;
use GuzzleHttp\Exception\ClientException;


/**
 * Class ApiCore
 *
 */
class ApiWageBase
{
    /**
     * @var WageBase
     */
    private $wageBase;

    /**
     * ApiCore constructor.
     *
     * @param WageBase $wageBase
     */
    public function __construct(WageBase $wageBase)
    {
        $this->wageBase = $wageBase;
    }

    /**
     * @param array $body
     * @param string $endpoint
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ApiPostRequest($body, $url)
    {
        $endpoint = $this->wageBase->baseUrl . $url;
        try {
            $response = $this->wagepayPostRequest($body, $endpoint);
            if ($response != false) {
                return json_decode($response->getBody());
            } else {
                throw new Exception(get_class($this) . " Error processing the transaction with mywagepay.com.");
            }
        } catch (ClientException $exception) {
            throw $this->generateException($exception);
        } catch (Exception $exception) {
            throw new Exception(get_class($this) . " " . $exception->getMessage());
        }
    }
    /**
     * @param array $body
     * @param string $endpoint
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleExceptions
     *
     */
    private function wagepayPostRequest($body, $endpoint)
    {
        try {
            return $this->wageBase->client->request(
                'POST',
                $endpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->wageBase->auth->authenticate(),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $body,
                ]
            );
        } catch (ClientException $xe) {
            $this->generateException($xe);
            return false;
        }
    }


    /**
     * @param array $body
     * @param string $endpoint
     * @return mixed
     * @throws Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function ApiGetRequest($body, $url)
    {
        $endpoint = $this->wageBase->baseUrl . $url;
        try {
            $response = $this->wagepayGetRequest($body, $endpoint);
            if ($response != false) {
                return json_decode($response->getBody());
            } else {
                throw new Exception(get_class($this) . " Error processing the transaction with mywagepay.com.");
            }
        } catch (ClientException $exception) {
            throw $this->generateException($exception);
        } catch (Exception $exception) {
            throw new Exception(get_class($this) . " " . $exception->getMessage());
        }
    }
    /**
     * @param array $body
     * @param string $endpoint
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleExceptions
     *
     */
    private function wagepayGetRequest($body, $endpoint)
    {
        try {
            return $this->wageBase->client->request(
                'GET',
                $endpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->wageBase->auth->authenticate(),
                        'Content-Type' => 'application/json',
                    ],
                    'query' => $body
                ]
            );
        } catch (ClientException $e) {
            Log::info($e->getRequest());
            $this->generateException($e);
        }
    }
    /**
     * @param ClientException $exception
     */
    private function generateException(ClientException $exception)
    {
        throw new Exception($exception->getResponse()->getBody());
    }
}
