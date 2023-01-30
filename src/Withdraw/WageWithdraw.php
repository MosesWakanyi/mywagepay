<?php

namespace myWagepay\Baas\Withdraw;

use Exception;
use Illuminate\Support\Facades\Log;
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
    private $withdrawableAmount;
    /**
     * @var string
     */
    private $myWagepayId;
    /**
     * @var string
     */
    private $trials = 3;
    /**
     * @var string
     */
    private $callback;
    /**
     * Set number to receive the funds
     *
     * @param $businessNumber
     * @return $this
     * @internal param string $number
     */
    public function to($myWagepayId)
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
    public function amount($amount)
    {
        if (is_numeric($amount)) {
            new Exception("withdrawable amount must be a number");
        }
        $this->withdrawableAmount = $amount;
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
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws Exception
     */
    public function call()
    {
        $data = [
            'withdraw_amount' => $this->withdrawableAmount,
            'myWagepayId' => $this->myWagepayId,
            'callback' => $this->callback,
        ];
        try {
            return $this->ApiPostRequest($data, 'baas/withdraw');
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
