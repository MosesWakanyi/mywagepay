<?php

namespace myWagepay\Baas\Trait;

use Exception;
use Illuminate\Support\Facades\Log;
use myWagepay\Baas\Facade\WageOwed;
use myWagepay\Baas\Facade\WageBorrow;
use myWagepay\Baas\Facade\WageCustomer;
use myWagepay\Baas\Facade\WageRepayment;
use myWagepay\Baas\Facade\WageUpdateLimit;
use myWagepay\Baas\Facade\WageCustomerUpdate;

trait Borrowable
{
    public function createAsWagepay()
    {
        try {
            $wageUser = WageCustomer::phone($this->membership->phone_number)
                ->nat($this->membership->id_number)
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
        return  $wageUser;
    }
    public function updateAsWagepay($options = [])
    {
        return WageCustomerUpdate::to($this->mywagepay_id)
            ->setOptions($options)
            ->call();
    }

    public function createLimitAsWagepay($creditLimit = 0)
    {
        return WageUpdateLimit::to($this->mywagepay_id)
            ->phone($this->membership->phone_number)
            ->newlimit($creditLimit)
            ->call();
    }

    public function borrowAsWagepay($borrowedAmount, $dealineDate)
    {
        return WageBorrow::to($this->mywagepay_id)
            ->amount($borrowedAmount)
            ->deadline($dealineDate)
            ->call();
    }
    public function loanListAsWagepay()
    {
        return WageOwed::to($this->mywagepay_id)->call();
    }
    public function payAsWagepay($amountToPay, $referenceCode, $phoneNumber = '')
    {
        return WageRepayment::from($this->mywagepay_id)
            ->phone(isset($phoneNumber) ?? $this->membership->phone_number)
            ->amount($amountToPay)
            ->reference($referenceCode)
            ->call();
    }
    public function withdrawAsWagepay($withdrawableAmount)
    {
        return WageRepayment::to($this->mywagepay_id)
            ->amount($withdrawableAmount)
            ->call();
    }
}
