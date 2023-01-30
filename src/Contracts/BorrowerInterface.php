<?php

namespace myWagepay\Baas\Contracts;

use App\Models\User;

interface BorrowerInterface
{
    /**
     * Create user.
     *
     * @return string
     */
    public function createAsWagepay();

    /**
     * update user.
     *
     * @return string
     */
    public function updateAsWagepay($options);

    /**
     * createLimit user.
     *

     * @param float $creditLimit
     * @return string
     */
    public function createLimitAsWagepay(float $creditLimit);

    /**
     * borrow user.
     *
     * @param \User\User $User
     * @param float $borrowedAmount
     * @return string
     */
    public function borrowAsWagepay(float $borrowedAmount);

    /**
     * user loanList
     *
     * @return string
     */
    public function loanListAsWagepay();

    /**
     * pay user.
     *
     * @param \User\User $User
     * @param float $amountToPay
     * @param string $phoneNumber
     * @param string $referenceCode
     * @return string
     */
    public function payAsWagepay(float $amountToPay, string $phoneNumber, string $referenceCode);
    /**
     * withdraw user.
     *
     * @param \User\User $User
     * @param float $withdrawableAmount
     * @return string
     */
    public function withdrawAsWagepay(float $withdrawableAmount);
}
