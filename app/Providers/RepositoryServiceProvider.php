<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\ProductRepositoryInterface;
use App\Contracts\OrderRepositoryInterface;
use App\Contracts\CartRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\CartRepository;

/**
 * Dependency Inversion Principle (DIP)
 * Service Provider untuk binding interface ke concrete implementation
 * Memungkinkan dependency injection dan loose coupling
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces to Concrete Implementations
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}