<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_product',
        'jumlah',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
