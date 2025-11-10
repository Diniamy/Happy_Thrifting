<?php

namespace App\Services;

use App\Contracts\CartRepositoryInterface;
use App\Models\Cart;
use Illuminate\Database\Eloquent\Collection;

/**
 * Single Responsibility Principle (SRP)
 * Service ini hanya bertanggung jawab untuk business logic Cart
 */
class CartService
{
    protected CartRepositoryInterface $cartRepository;
    protected ProductService $productService;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        ProductService $productService
    ) {
        $this->cartRepository = $cartRepository;
        $this->productService = $productService;
    }

    /**
     * Add item to cart
     */
    public function addToCart(int $userId, int $productId, int $quantity = 1): Cart
    {
        // Validate product exists and has stock
        $product = $this->productService->findProduct($productId);
        if (!$product) {
            throw new \Exception('Product not found');
        }

        if (!$this->productService->hasStock($productId, $quantity)) {
            throw new \Exception('Insufficient stock');
        }

        return $this->cartRepository->addItem($userId, $productId, $quantity);
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity(int $userId, int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($userId, $productId);
        }

        // Validate stock
        if (!$this->productService->hasStock($productId, $quantity)) {
            throw new \Exception('Insufficient stock');
        }

        return $this->cartRepository->updateQuantity($userId, $productId, $quantity);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart(int $userId, int $productId): bool
    {
        return $this->cartRepository->removeItem($userId, $productId);
    }

    /**
     * Get user cart items
     */
    public function getCartItems(int $userId): Collection
    {
        return $this->cartRepository->getByUser($userId);
    }

    /**
     * Get cart total price
     */
    public function getCartTotal(int $userId): float
    {
        return $this->cartRepository->getTotalPrice($userId);
    }

    /**
     * Get cart items count
     */
    public function getCartItemsCount(int $userId): int
    {
        return $this->cartRepository->getItemsCount($userId);
    }

    /**
     * Clear user cart
     */
    public function clearCart(int $userId): bool
    {
        return $this->cartRepository->clearCart($userId);
    }

    /**
     * Validate cart items stock before checkout
     */
    public function validateCartStock(int $userId): array
    {
        $cartItems = $this->getCartItems($userId);
        $errors = [];

        foreach ($cartItems as $item) {
            if (!$this->productService->hasStock($item->id_product, $item->jumlah)) {
                $errors[] = "Insufficient stock for {$item->products->nama_produk}";
            }
        }

        return $errors;
    }
}