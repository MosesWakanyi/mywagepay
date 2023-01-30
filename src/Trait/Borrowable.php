<?php

namespace myWagepay\Baas\Trait;

use Exception;
use Illuminate\Support\Facades\Log;
use myWagepay\Baas\Facade\WageOwed;
use myWagepay\Baas\Facade\WageBorrow;
use myWagepay\Baas\Facade\WageCustomer;
use myWagepay\Baas\Facade\WageRepayment;
use myWagepay\Baas\Facade\WageCustomerUpdate;

trait Borrowable
{
    public function createAsWagepay()
    {
        try {
            $wageUser = WageCustomer::phone($this->membership->phone_number)
                ->nat($this->membership->phone_number)
                ->email($this->email)
                ->fname($this->membership->first_name)
                ->lname($this->membership->last_name)
                ->dob($this->membership->dob)
                ->gender('others')
                ->limit(0)
                ->call();
            if ($wageUser) {
                $this->update(['mywagepay_id' => $wageUser->data->wage_uid]);
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
    public function updateAsWagepay($options = [])
    {
        $wageUser = WageCustomerUpdate::to()->setOptions();
    }

    public function createLimitAsWagepay($creditLimit = 0)
    {
        $wageLimit = WageCustomerUpdate::to()->phone()->newlimit();
    }

    public function borrowAsWagepay($borrowedAmount)
    {
        $wageBorrow = WageBorrow::to()->amount()->deadline();
    }
    public function loanListAsWagepay()
    {
        $wageloans = WageOwed::to();
    }
    public function payAsWagepay($amountToPay, $phoneNumber, $referenceCode)
    {
        $wageMakepay = WageRepayment::from()->phone()->amount()->reference();
    }
    public function withdrawAsWagepay($withdrawableAmount)
    {
        $wageWithdraw = WageRepayment::to()->amount();
    }
}
