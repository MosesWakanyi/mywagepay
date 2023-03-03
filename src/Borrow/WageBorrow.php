<?php

namespace myWagepay\Baas\Borrow;

use Exception;
use Illuminate\Support\Facades\Log;
use myWagepay\Baas\Request\ApiWageBase;
use GuzzleHttp\Exception\ServerException;


/**
 * Class BankSender
 *
 */
class WageBorrow extends ApiWageBase
{
    /**
     * @var double
     */
    private $borrowedAmount;
    /**
     * @var double
     */
    private $interestRate;
    /**
     * @var string
     */
    private $deadlineDate;
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
            new Exception("Amount borrowed must be a number");
        }
        $this->borrowedAmount = $amount;
        return $this;
    }
    /**
     * The amount to transact
     *
     * @param  $amount
     * @return $this
     */
    public function setInterestRate($amount)
    {
        if (is_numeric($amount)) {
            new Exception("Amount borrowed must be a number");
        }
        $this->interestRate = $amount;
        return $this;
    }
    /**
     * The amount to transact
     *
     * @param  $amount
     * @return $this
     */
    public function deadline($deadlineDate)
    {
        $this->deadlineDate = $deadlineDate;
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
            'borrowed_amount' => $this->borrowedAmount,
            'interest_rate' => $this->interestRate,
            'deadline_date' => $this->deadlineDate,
            'myWagepayId' => $this->myWagepayId,
        ];

        try {
            return $this->ApiPostRequest($data, 'baas/advance/request');
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
