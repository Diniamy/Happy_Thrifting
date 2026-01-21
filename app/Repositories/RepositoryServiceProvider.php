<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\ReadProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\CachedProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repository utama (CRUD)
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        // Repository read-only (OCP + LSP)
        $this->app->bind(
            ReadProductRepositoryInterface::class,
            CachedProductRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
