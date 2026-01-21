<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Single Responsibility Principle (SRP)
 * Class ini hanya bertanggung jawab untuk operasi data Product
 * 
 * Dependency Inversion Principle (DIP)
 * Mengimplementasikan interface, bukan bergantung pada concrete class
 */
class ProductRepository implements ProductRepositoryInterface
{
    protected Product $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }
 
    public function getAll(): Collection
    {
        return $this->model->with('kategori')->get();
    }

    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with('kategori')->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return $this->model->with('kategori')->find($id);
    }

    public function getByCategory(int $categoryId): Collection
    {
        return $this->model->where('id_kategori', $categoryId)
                          ->with('kategori')
                          ->get();
    }

    public function searchByName(string $name): Collection
    {
        return $this->model->where('nama_produk', 'LIKE', "%{$name}%")
                          ->with('kategori')
                          ->get();
    }

    public function getLowStock(int $threshold = 10): Collection
    {
        return $this->model->where('jumlah_produk', '<=', $threshold)
                          ->with('kategori')
                          ->get();
    }

    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $product = $this->findById($id);
        if (!$product) {
            return false;
        }

        return $product->update($data);
    }

    public function delete(int $id): bool
    {
        $product = $this->findById($id);
        if (!$product) {
            return false;
        }

        return $product->delete();
    }

    public function updateStock(int $id, int $quantity): bool
    {
        $product = $this->findById($id);
        if (!$product) {
            return false;
        }

        $product->jumlah_produk -= $quantity;
        return $product->save();
    }
}