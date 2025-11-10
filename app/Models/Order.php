<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'jumlah',
        'total_harga',
        'status',
        'payment_name',
        'payment_number',
        'bukti_transfer',
        'bank_id',
        'catatan_admin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_order');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
