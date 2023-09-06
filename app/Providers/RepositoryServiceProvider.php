<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(
            'App\Interfaces\Transactions\TransactionRepositoryInterface',
            'App\Repositories\Transactions\TransactionRepository');

        $this->app->bind(
            'App\Interfaces\Users\UserRepositoryInterface',
            'App\Repositories\Users\UserRepository');
    }
}
