<?php

namespace App\Contracts;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface Segregation Principle (ISP)
 * Interface khusus untuk Order operations
 */
interface OrderRepositoryInterface
{
    /**
     * Create new order
     */
    public function create(array $data): Order;

    /**
     * Find order by ID
     */
    public function findById(int $id): ?Order;

    /**
     * Get orders by user
     */
    public function getByUser(int $userId): Collection;

    /**
     * Update order status
     */
    public function updateStatus(int $id, string $status): bool;

    /**
     * Get orders by status
     */
    public function getByStatus(string $status): Collection;

    /**
     * Get all orders with pagination
     */
    public function getPaginated(int $perPage = 15);
}