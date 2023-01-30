<?php

namespace myWagepay\Baas;

use GuzzleHttp\Client;
use myWagepay\Baas\Borrow\WageOwed;
use myWagepay\Baas\Request\WageBase;
use myWagepay\Baas\Borrow\WageBorrow;
use Illuminate\Support\ServiceProvider;
use myWagepay\Baas\Borrow\WageRepayment;
use myWagepay\Baas\Customer\WageCustomer;
use myWagepay\Baas\Withdraw\WageWithdraw;
use myWagepay\Baas\Customer\WageUpdateLimit;
use myWagepay\Baas\Customer\WageCustomerUpdate;



class MyWagepayServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/mywagepay.php' => config_path('mywagepay.php'),
            ], 'config');

            $this->publishes([
                __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
            ], 'mywagepay-migrations');
        }
    }
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->app->singleton(WageBase::class, function ($app) {
            return new WageBase(new Client);
        });
        $this->registerFacade();
    }


    function registerFacade()
    {
        $this->app->bind('wage_customer', function () {
            return $this->app->make(WageCustomer::class);
        });

        $this->app->bind('wage_customer_update', function () {
            return $this->app->make(WageCustomerUpdate::class);
        });
        $this->app->bind('wage_update_limit', function () {
            return $this->app->make(WageUpdateLimit::class);
        });
        $this->app->bind('wage_borrow', function () {
            return $this->app->make(WageBorrow::class);
        });
        $this->app->bind('wage_owed', function () {
            return $this->app->make(WageOwed::class);
        });
        $this->app->bind('wage_repayment', function () {
            return $this->app->make(WageRepayment::class);
        });
        $this->app->bind('wage_withdraw', function () {
            return $this->app->make(WageWithdraw::class);
        });
    }
}
