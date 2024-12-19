<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\ContactRepositoryInterface;
use App\Repository\ContactRepository;
use App\Repository\UserRepositoryInterface;
use App\Repository\UserRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

    }
}
