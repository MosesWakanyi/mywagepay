<?php

namespace myWagepay\Baas\Trait;

use Exception;
use myWagepay\Baas\Facade\WageOwed;
use myWagepay\Baas\Facade\WageBorrow;
use myWagepay\Baas\Facade\WageCustomer;
use myWagepay\Baas\Facade\WageWithdraw;
use myWagepay\Baas\Facade\WageRepayment;
use Illuminate\Support\Facades\Validator;
use myWagepay\Baas\Facade\WageUpdateLimit;
use myWagepay\Baas\Facade\WageCustomerUpdate;


trait Borrowable
{
    public function createAsWagepay($params)
    {

        $validation = Validator::make($params, [
            'phone_number' => 'required',
            'id_number' => 'required',
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required|date_format:Y-m-d',
            'gender' => 'required',
        ]);
        if ($validation->fails()) {
            throw new Exception(json_encode($validation->messages()));
        }
        $wageUser = WageCustomer::phone($params['phone_number'])
            ->nat($params['id_number'])
            ->email($params['email'])
            ->fname($params['first_name'])
            ->lname($params['last_name'])
            ->dob($params['dob'])
            ->gender($params['gender'])
            ->limit(0)
            ->call();
        if ($wageUser) {
            $this->update(['mywagepay_id' => $wageUser->data->wage_uid]);
            return  $wageUser->data;
        } else {
            throw new Exception("Could not initiate account with myWagepay");
            return false;
        }
    }
    public function updateAsWagepay($options = [])
    {
        return WageCustomerUpdate::to($this->mywagepay_id)
            ->setOptions($options)
            ->call();
    }

    public function createLimitAsWagepay($params = [])
    {

        $validation = Validator::make($params, [
            'phone_number' => 'required',
            'credit_limit' => 'required|numeric',
        ]);
        if ($validation->fails()) {
            throw new Exception(json_encode($validation->messages()));
        }
        return WageUpdateLimit::to($this->mywagepay_id)
            ->phone($params['phone_number'])
            ->newlimit($params['credit_limit'])
            ->call();
    }

    public function borrowAsWagepay($params = [])
    {

        $validation = Validator::make($params, [
            'deadline_date' => 'required|date_format:Y-m-d',
            'interest_rate' => 'required|numeric',
            'amount' => 'required|numeric',
        ]);
        if ($validation->fails()) {
            throw new Exception(json_encode($validation->errors()->all()));
        }
        return WageBorrow::to($this->mywagepay_id)
            ->amount($params['amount'])
            ->setInterestRate($params['interest_rate'])
            ->deadline($params['deadline_date'])
            ->call();
    }
    public function loanListAsWagepay()
    {
        return WageOwed::for($this->mywagepay_id)->call();
    }
    public function payAsWagepay($params = [])
    {

        $validation = Validator::make($params, [
            'phone_number' => 'required',
            'payable_amount' => 'required|numeric',
            'reference_code' => 'required',
        ]);
        if ($validation->fails()) {
            throw new Exception(json_encode($validation->errors()->all()));
        }
        return WageRepayment::from($this->mywagepay_id)
            ->phone($params['phone_number'])
            ->amount($params['payable_amount'])
            ->reference($params['reference_code'])
            ->call();
    }
    public function withdrawAsWagepay($params = [])
    {

        $validation = Validator::make($params, [
            'withdrawable_amount' => 'required|numeric',
            'phone_number' => 'required',
            'withdraw_desc' => 'required',
        ]);
        if ($validation->fails()) {
            throw new Exception(json_encode($validation->errors()->all()));
        }
        return WageWithdraw::to($this->mywagepay_id)
            ->amount($params['withdrawable_amount'])
            ->phone($params['phone_number'])
            ->description($params['withdraw_desc'])
            ->call();
    }
}
