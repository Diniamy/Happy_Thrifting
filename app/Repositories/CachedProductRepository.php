<?php

namespace App\Repositories;

use App\Contracts\ReadProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

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
