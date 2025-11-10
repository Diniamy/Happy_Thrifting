<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_order',
        'id_product',
        'jumlah',
        'harga_satuan',
    ];


    // Relasi ke tabel produk
    public function products()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    // Relasi ke tabel order
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
