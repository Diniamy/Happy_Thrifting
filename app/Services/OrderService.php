<?php

namespace App\Services;

use App\Contracts\OrderRepositoryInterface;
use App\Contracts\CartRepositoryInterface;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Single Responsibility Principle (SRP)
 * Service ini hanya bertanggung jawab untuk business logic Order
 * 
 * Open/Closed Principle (OCP)
 * Dapat di-extend untuk payment methods baru tanpa mengubah existing code
 */
class OrderService
{
    protected OrderRepositoryInterface $orderRepository;
    protected CartRepositoryInterface $cartRepository;
    protected ProductService $productService;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CartRepositoryInterface $cartRepository,
        ProductService $productService
    ) {
        $this->orderRepository = $orderRepository;
        $this->cartRepository = $cartRepository;
        $this->productService = $productService;
    }

    /**
     * Create order from cart
     */
    public function createOrderFromCart(int $userId): Order
    {
        /** @var Order $result */
        $result = DB::transaction(function () use ($userId) {
            $cartItems = $this->cartRepository->getByUser($userId);
            
            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            // Validate stock availability
            foreach ($cartItems as $item) {
                if (!$this->productService->hasStock($item->id_product, $item->jumlah)) {
                    throw new \Exception("Insufficient stock for product: {$item->products->nama_produk}");
                }
            }

            // Calculate total
            $totalPrice = $this->cartRepository->getTotalPrice($userId);

            // Create order
            $order = $this->orderRepository->create([
                'id_user' => $userId,
                'total_harga' => $totalPrice,
                'status' => 'waiting_payment',
            ]);

            // Create order items and update stock
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'id_order' => $order->id,
                    'id_product' => $item->id_product,
                    'jumlah' => $item->jumlah,
                    'harga_satuan' => $item->products->harga_produk,
                ]);

                $this->productService->updateStock($item->id_product, -$item->jumlah);
            }

            // Clear cart
            $this->cartRepository->clearCart($userId);

            return $order;
        });
        
        return $result;
    }

    /**
     * Create direct buy order
     */
    public function createDirectOrder(int $userId, int $productId, int $quantity): Order
    {
        /** @var Order $result */
        $result = DB::transaction(function () use ($userId, $productId, $quantity) {
            if (!$this->productService->hasStock($productId, $quantity)) {
                throw new \Exception('Insufficient stock');
            }

            $product = $this->productService->findProduct($productId);
            $totalPrice = $product->harga_produk * $quantity;

            // Create order
            $order = $this->orderRepository->create([
                'id_user' => $userId,
                'total_harga' => $totalPrice,
                'status' => 'waiting_payment',
            ]);

            // Create order item
            OrderItem::create([
                'id_order' => $order->id,
                'id_product' => $productId,
                'jumlah' => $quantity,   
                'harga_satuan' => $product->harga_produk,
            ]);

            // Update stock
            $this->productService->updateStock($productId, -$quantity);

            return $order;
        });
        
        return $result;
    }

    /**
     * Get user orders
     */
    public function getUserOrders(int $userId): Collection
    {
        return $this->orderRepository->getByUser($userId);
    }

    /**
     * Get order by ID
     */
    public function getOrder(int $orderId): ?Order
    {
        return $this->orderRepository->findById($orderId);
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(int $orderId, string $status): bool
    {
        return $this->orderRepository->updateStatus($orderId, $status);
    }

    /**
     * Get orders by status
     */
    public function getOrdersByStatus(string $status): Collection
    {
        return $this->orderRepository->getByStatus($status);
    }

    /**
     * Get all orders with pagination
     */
    public function getAllOrders(int $perPage = 15)
    {
        return $this->orderRepository->getPaginated($perPage);
    }
}