<?php

namespace myWagepay\Baas\Customer;

use Exception;
use Illuminate\Support\Facades\Log;
use myWagepay\Baas\Request\ApiWageBase;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Validator;

/**
 * Class WageOboarding
 *
 */
class WageCustomer extends ApiWageBase
{
    /**
     * @var string
     */
    private $phone;
    /**
     * @var string
     */
    private $nationalId;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastName;

    /* @var string
     */
    private $dateOfBirth;
    /* 
     * @var string
     */
    private $gender;
    /* 
     * @var string
     */
    private $creditLimit = 0;
    /* 
     * @var int
     */
    private $trials = 3;
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
     * The amount to transact
     *
     * @param  $nationalId
     * @return $this
     */
    public function nat($nationalId)
    {
        if (!is_numeric($nationalId)) {
            new Exception("national number must numeric");
        }
        $this->nationalId = $nationalId;
        return $this;
    }

    /**
     * @param string $email
     * @return $this
     * @internal param string $email
     */
    public function email($email)
    {
        $validator = Validator::make(['email' => $email], ['email' => 'required|email']);
        if ($validator->fails()) {
            new Exception($validator->errors());
        }
        $this->email = $email;
        return $this;
    }

    /**
     * @param int $firstName
     * @return $this
     * @internal param string $firstName
     */
    public function fname($firstName)
    {
        if (empty($firstName)) {
            new Exception("first name should not be empty");
        }
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param string $lastName
     * @return $this
     * @internal param string $lastName
     */
    public function lname($lastName)
    {
        if (empty($lastName)) {
            new Exception("last name should not be empty");
        }
        $this->lastName = $lastName;
        return $this;
    }
    /**
     * @param string $dateOfBirth
     * @return $this
     * @internal param string $dateOfBirth
     */
    public function dob($dateOfBirth)
    {
        if (empty($dateOfBirth)) {
            new Exception("Date of birth should not be empty");
        }
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * @param string $gender
     * @return $this
     * @internal param string $gender
     */
    public function gender($gender)
    {
        if (empty($gender)) {
            new Exception("Gender of should not be empty");
        }
        $this->gender = $gender;
        return $this;
    }

    /**
     * @param string $gender
     * @return $this
     * @internal param string $gender
     */
    public function limit($creditLimit)
    {
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
            $data = [
                'phone_number' => $this->phone,
                'national_id' => $this->nationalId,
                'email' => $this->email,
                'first_name' => $this->firstName,
                'last_name' => $this->lastName,
                'date_of_birth' => $this->dateOfBirth,
                'gender' => $this->gender,
                'advance_limit' => $this->creditLimit
            ];
            return $this->ApiPostRequest($data, 'baas/account/create');
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
