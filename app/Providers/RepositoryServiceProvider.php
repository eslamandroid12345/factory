<?php

namespace App\Providers;

use App\Http\Interfaces\AdminRepositoryInterface;
use App\Http\Interfaces\DeveloperRepositoryInterface;
use App\Http\Repositories\AdminRepository;
use App\Http\Repositories\DeveloperRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DeveloperRepositoryInterface::class,DeveloperRepository::class);
        $this->app->bind(AdminRepositoryInterface::class,AdminRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */

}
