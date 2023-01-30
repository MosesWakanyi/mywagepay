<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Account
    |--------------------------------------------------------------------------
    |
    | This is the default account to be used when none is specified.
    */

    'default' => 'staging',

    /*
    |--------------------------------------------------------------------------
    | Native File Cache Location
    |--------------------------------------------------------------------------
    |
    | When using the Native Cache driver, this will be the relative directory
    | where the cache information will be stored.
    */

    'cache_location' => '../cache',

    /*
    |--------------------------------------------------------------------------
    | Accounts
    |--------------------------------------------------------------------------
    |
    | These are the accounts that can be used with the package. You can configure
    | as many as needed. Two have been setup for you.
    |
    | Sandbox: Determines whether to use the sandbox, Possible values: sandbox | production
    | Initiator: This is the username used to authenticate the transaction request
    */

    'consumer_key' => env('CONSUMER_KEY', ""),
    'consumer_secret' =>  env('CONSUMER_SECRET', ""),
];
