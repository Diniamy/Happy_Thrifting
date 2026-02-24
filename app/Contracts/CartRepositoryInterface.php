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
    public function getByUser(int $userId): Collection;
    public function addItem(int $userId, int $productId, int $quantity = 1): Cart;
    public function updateQuantity(int $userId, int $productId, int $quantity): bool;
    public function removeItem(int $userId, int $productId): bool;
    public function clearCart(int $userId): bool;
    public function getTotalPrice(int $userId): float;
    public function getItemsCount(int $userId): int;
}

