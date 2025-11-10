<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface Segregation Principle (ISP)
 * Interface ini hanya berisi method yang benar-benar dibutuhkan untuk Product operations
 */
interface ProductRepositoryInterface
{
    /**
     * Get all products
     */
    public function getAll(): Collection;

    /**
     * Get products with pagination
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator;

    /**
     * Find product by ID
     */
    public function findById(int $id): ?Product;

    /**
     * Get products by category
     */
    public function getByCategory(int $categoryId): Collection;

    /**
     * Search products by name
     */
    public function searchByName(string $name): Collection;

    /**
     * Get products with low stock
     */
    public function getLowStock(int $threshold = 10): Collection;

    /**
     * Create new product
     */
    public function create(array $data): Product;

    /**
     * Update product
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete product
     */
    public function delete(int $id): bool;

    /**
     * Update product stock
     */
    public function updateStock(int $id, int $quantity): bool;
}