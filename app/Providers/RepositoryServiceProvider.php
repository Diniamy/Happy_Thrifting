<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Interfaces
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\ReadProductRepositoryInterface;
use App\Contracts\OrderRepositoryInterface;
use App\Contracts\CartRepositoryInterface;

// Implementations
use App\Repositories\ProductRepository;
use App\Repositories\CachedProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CartRepository;

/**
 * DIP  : Binding interface ke concrete class
 * OCP  : Menambah repository cache TANPA mengubah repository lama
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // === PRODUCT ===
        // Repository utama (CRUD)
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        // Repository read-only (CACHE)
        // 👉 OCP + LSP
        $this->app->bind(
            ReadProductRepositoryInterface::class,
            CachedProductRepository::class
        );

        // === ORDER ===
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        // === CART ===
        $this->app->bind(
            CartRepositoryInterface::class,
            CartRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
