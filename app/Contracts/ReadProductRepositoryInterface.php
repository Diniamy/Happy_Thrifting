<?php

namespace App\Contracts;

use App\Models\Product;
/**
 * Interface Segregation Principle (ISP)
 * Interface khusus untuk operasi read-only Product
 */

interface ReadProductRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
}
