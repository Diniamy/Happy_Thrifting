<?php

namespace App\Contracts;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface Segregation Principle (ISP)
 * Interface khusus untuk Cart operations
 */
interface CartRepositoryInterface
{
    /**
     * Get cart items by user
     */
    public function getByUser(int $userId): Collection;

    /**
     * Add item to cart
     */
    public function addItem(int $userId, int $productId, int $quantity = 1): Cart;

    /**
     * Update cart item quantity
     */
    public function updateQuantity(int $userId, int $productId, int $quantity): bool;

    /**
     * Remove item from cart
     */
    public function removeItem(int $userId, int $productId): bool;

    /**
     * Clear all cart items for user
     */
    public function clearCart(int $userId): bool;

    /**
     * Get cart total price
     */
    public function getTotalPrice(int $userId): float;

    /**
     * Get cart items count
     */
    public function getItemsCount(int $userId): int;
}