<?php

namespace myWagepay\Baas\Withdraw;

use Exception;
use myWagepay\Baas\Request\ApiWageBase;
use GuzzleHttp\Exception\ServerException;


/**
 * Class BankSender
 *
 */
class WageWithdraw extends ApiWageBase
{
    /**
     * @var double
     */
    private $amountToPay;
    /**
     * @var double
     */
    private $phoneNumber;
    /**

     /**
     * @var string
     */
    private $withdrawDesc;
    /**
     * @var string
     */
    private $myWagepayId;
    /**
     * @var string
     */
    private $callback;
    /**
     * @var int
     */
    private $trials = 3;

    /**
     * Set number to receive the funds
     *
     * @param $businessNumber
     * @return $this
     * @internal param string $number
     */
    public function from($myWagepayId)
    {
        if (empty($myWagepayId)) {
            new Exception("myWagepay ID cannot be empty");
        }
        $this->myWagepayId = $myWagepayId;
        return $this;
    }

    /**
     * The amount to transact
     *
     * @param  $amount
     * @return $this
     */
    public function phone($phoneNumber)
    {
        if (empty($phoneNumber)) {
            new Exception("Phone number is required");
        }
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * The amount to transact
     *
     * @param  $amount
     * @return $this
     */
    public function amount($amount)
    {
        if (is_numeric($amount)) {
            new Exception("Amount payable must be a number");
        }
        $this->amountToPay = $amount;
        return $this;
    }
    /**
     * The amount to transact
     *
     * @param  $amount
     * @return $this
     */
    public function description($withdrawDesc)
    {
        if (empty($withdrawDesc)) {
            new Exception("Description must provided");
        }
        $this->withdrawDesc = $withdrawDesc;
        return $this;
    }

    /**
     * @param int $callback
     * @return $this
     * @internal param string $callback
     */
    public function callback($callback)
    {
        $this->callback = $callback;
        return $this;
    }

    /**
     * @param string|null $number
     * @param int|null $amount
     * @param string|null $serviceProvider
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function call()
    {
        $data = [
            'withdraw_amount' => $this->amountToPay,
            'phone_number' => $this->phoneNumber,
            'withdraw_desc'=>$this->withdrawDesc,
            'myWagepayId' => $this->myWagepayId,
        ];
        try {
            return $this->ApiPostRequest($data, 'baas/withdrawal');
        } catch (ServerException $exception) {
            if ($this->trials > 0) {
                $this->trials--;
                return $this->call();
            }
        }
        return false;
    }
}
