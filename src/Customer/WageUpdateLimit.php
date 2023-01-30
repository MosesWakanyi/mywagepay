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
class WageUpdateLimit extends ApiWageBase
{
    /**
     * @var string
     */
    private $phone;
    /**
     * @var string
     */
    private $myWagepayId;
    /** 
     * @var double
     */
    private $creditLimit = 0;
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
    public function phone($phone)
    {
        if (!is_numeric($phone)) {
            new Exception("Phone number must numeric");
        }
        $this->phone = $phone;
        return $this;
    }
    /**
     * @param double $creditLimit
     * @return $this
     * @internal param double $creditLimit
     */
    public function newlimit($creditLimit)
    {
        if (!is_numeric($creditLimit)) {
            new Exception("Credit Limit must numeric");
        }
        $this->creditLimit = $creditLimit;
        return $this;
    }

    /* @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function call()
    {
        try {
            $requestBody = [
                'phone_number' => $this->phone,
                'myWagepayId' => $this->myWagepayId,
                'advance_limit' => $this->creditLimit
            ];
            return $this->ApiPostRequest($requestBody, 'baas/account/limit/update');
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
