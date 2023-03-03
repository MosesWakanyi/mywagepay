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
    public function createAsWagepay(array $params);

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
    public function createLimitAsWagepay(array $params);

    /**
     * borrow user.
     *
     * @param \User\User $User
     * @param array $params
     * @return string
     */
    public function borrowAsWagepay(array $params);

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
     * @param array $params
     * @return string
     */
    public function payAsWagepay(array $params);
    /**
     * withdraw user.
     *
     * @param \User\User $User
     * @param array $params
     * @return string
     */
    public function withdrawAsWagepay(array $params);
}
