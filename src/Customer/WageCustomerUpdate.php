<?php

namespace myWagepay\Baas\Customer;

use Exception;
use Illuminate\Support\Facades\Log;
use myWagepay\Baas\Request\ApiWageBase;
use GuzzleHttp\Exception\ServerException;

/**
 * Class WageOboarding
 *
 */
class WageCustomerUpdate extends ApiWageBase
{

    /**
     * @var string
     */
    private $myWagepayId;
    /**
     * @var string
     */
    private $options;
    /** 
     * @var int
     */
    private $trials = 3;

    /**
     * @param double $creditLimit
     * @return $this
     * @internal param double $creditLimit
     */
    public function to($myWagepayId)
    {
        if (!is_numeric($myWagepayId)) {
            new Exception("Credit Limit must numeric");
        }
        $this->myWagepayId = $myWagepayId;
        return $this;
    }
    /**
     * 
     * Set number to receive the funds
     *
     * @param $phoneNumber
     * @return $this
     * @internal param string $phoneNumber
     */
    public function setCreditLimit(array $options)
    {
        if (!is_array($options)) {
            new Exception("options must be array");
        }
        $this->options = $options;
        return $this;
    }

    /* @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function call()
    {
        try {
            $data = [
                'options' => $this->options,
                'myWagepayId' => $this->myWagepayId,
            ];
            return $this->ApiPostRequest($data, 'baas/account/update');
        } catch (ServerException $exception) {
            if ($this->trials > 0) {
                $this->trials--;
                return $this->call();
            }
            Log::info($exception->getMessage());
        }
        return false;
    }
}
