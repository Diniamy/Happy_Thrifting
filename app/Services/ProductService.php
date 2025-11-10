<?php

namespace App\Services;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Single Responsibility Principle (SRP)
 * Service ini hanya bertanggung jawab untuk business logic Product
 * 
 * Open/Closed Principle (OCP)
 * Class ini terbuka untuk extension tapi tertutup untuk modification
 * 
 * Dependency Inversion Principle (DIP)
 * Bergantung pada abstraction (interface) bukan concrete class
 */
class ProductService
{
    protected ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products with optional filtering
     */
    public function getAllProducts(?int $categoryId = null, ?string $search = null): Collection
    {
        if ($categoryId) {
            return $this->productRepository->getByCategory($categoryId);
        }

        if ($search) {
            return $this->productRepository->searchByName($search);
        }

        return $this->productRepository->getAll();
    }

    /**
     * Get paginated products
     */
    public function getPaginatedProducts(int $perPage = 15)
    {
        return $this->productRepository->getPaginated($perPage);
    }

    /**
     * Find product by ID
     */
    public function findProduct(int $id): ?Product
    {
        return $this->productRepository->findById($id);
    }

    /**
     * Create new product with image upload
     */
    public function createProduct(array $data, ?UploadedFile $image = null): Product
    {
        if ($image) {
            $data['gambar_produk'] = $this->uploadProductImage($image);
        }

        return $this->productRepository->create($data);
    }

    /**
     * Update product with optional image upload
     */
    public function updateProduct(int $id, array $data, ?UploadedFile $image = null): bool
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            return false;
        }

        if ($image) {
            // Delete old image
            if ($product->gambar_produk) {
                Storage::disk('public')->delete($product->gambar_produk);
            }
            
            $data['gambar_produk'] = $this->uploadProductImage($image);
        }

        return $this->productRepository->update($id, $data);
    }

    /**
     * Delete product
     */
    public function deleteProduct(int $id): bool
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            return false;
        }

        // Delete image file
        if ($product->gambar_produk) {
            Storage::disk('public')->delete($product->gambar_produk);
        }

        return $this->productRepository->delete($id);
    }

    /**
     * Check if product has sufficient stock
     */
    public function hasStock(int $productId, int $requiredQuantity): bool
    {
        $product = $this->productRepository->findById($productId);
        return $product && $product->jumlah_produk >= $requiredQuantity;
    }

    /**
     * Update product stock
     */
    public function updateStock(int $productId, int $quantity): bool
    {
        return $this->productRepository->updateStock($productId, $quantity);
    }

    /**
     * Get products with low stock
     */
    public function getLowStockProducts(int $threshold = 10): Collection
    {
        return $this->productRepository->getLowStock($threshold);
    }

    /**
     * Upload product image
     */
    private function uploadProductImage(UploadedFile $image): string
    {
        return $image->store('products', 'public');
    }
}