<?php

namespace App\Repositories;

use App\Contracts\CartRepositoryInterface;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

/**
 * Single Responsibility Principle (SRP)
 * Class ini hanya bertanggung jawab untuk operasi data Cart
 */
class CartRepository implements CartRepositoryInterface
{
    protected Cart $model;

    public function __construct(Cart $model)
    {
        $this->model = $model;
    }

    public function getByUser(int $userId): Collection
    {
        return $this->model->where('id_user', $userId)
                          ->with('products')
                          ->get();
    }

    public function addItem(int $userId, int $productId, int $quantity = 1): Cart
    {
        $existingCart = $this->model->where('id_user', $userId)
                                   ->where('id_product', $productId)
                                   ->first();

        if ($existingCart) {
            $existingCart->jumlah += $quantity;
            $existingCart->save();
            return $existingCart;
        }

        return $this->model->create([
            'id_user' => $userId,
            'id_product' => $productId,
            'jumlah' => $quantity,
        ]);
    }

    public function updateQuantity(int $userId, int $productId, int $quantity): bool
    {
        $cartItem = $this->model->where('id_user', $userId)
                               ->where('id_product', $productId)
                               ->first();

        if (!$cartItem) {
            return false;
        }

        $cartItem->jumlah = $quantity;
        return $cartItem->save();
    }

    public function removeItem(int $userId, int $productId): bool
    {
        return $this->model->where('id_user', $userId)
                          ->where('id_product', $productId)
                          ->delete() > 0;
    }

    public function clearCart(int $userId): bool
    {
        return $this->model->where('id_user', $userId)->delete() > 0;
    }

    public function getTotalPrice(int $userId): float
    {
        $cartItems = $this->getByUser($userId);
        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += $item->products->harga_produk * $item->jumlah;
        }
        
        return $total;
    }

    public function getItemsCount(int $userId): int
    {
        return $this->model->where('id_user', $userId)->sum('jumlah');
    }
}