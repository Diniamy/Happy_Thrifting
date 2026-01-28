<?php

namespace App\Repositories;

use App\Contracts\ReadProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

/**
 * Liskov Substitution Principle (LSP)
 * Class ini dapat menggantikan implementasi repository lain
 * yang menggunakan ReadProductRepositoryInterface tanpa
 * mengubah cara kerja sistem.
 *
 * Open Closed Principle (OCP)
 * Penambahan fitur cache dilakukan melalui class baru
 * tanpa mengubah kode repository yang sudah ada.
 */
class CachedProductRepository implements ReadProductRepositoryInterface
{
    public function getAll()
    {
        return Cache::remember('products_all', 600, function () {
            return Product::with('kategori')->get();
        });
    }

    public function findById(int $id)
    {
        return Cache::remember("product_{$id}", 600, function () use ($id) {
            return Product::with('kategori')->find($id);
        });
    }
}
