<?php

namespace App\Contracts;

use App\Models\Product;

interface ReadProductRepositoryInterface
{
    public function getAll();
    public function findById(int $id);
}
