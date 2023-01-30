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
class WageOwed extends ApiWageBase
{

    /**
     * @var string
     */
    private $myWagepayId;
    /**
     * Set number to receive the funds
     *
     * @param $businessNumber
     * @return $this
     * @internal param string $number
     */
    public function for($myWagepayId)
    {
        if (empty($myWagepayId)) {
            new Exception("myWagepay ID cannot be empty");
        }
        $this->myWagepayId = $myWagepayId;
        return $this;
    }


    public function call()
    {
        $data = [
            'myWagepayId' => $this->myWagepayId,
        ];
        try {
            return $this->ApiGetRequest($data, 'baas/account/advance/');
        } catch (ServerException $exception) {
            Log::info($exception->getMessage());
        }
        return false;
    }
}
