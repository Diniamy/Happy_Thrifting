<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'id_user',
        'id_kategori',
        'nama_produk',
        'harga_produk',
        'jumlah_produk',
        'gambar_produk',
    ];

    protected $appends = ['gambar_url'];

    // Accessor untuk mendapatkan URL gambar yang benar
    public function getGambarUrlAttribute()
    {
        if ($this->gambar_produk) {
            // Cek apakah file ada di storage
            if (Storage::disk('public')->exists($this->gambar_produk)) {
                return asset('storage/' . $this->gambar_produk);
            }
        }
        // Return gambar default jika tidak ada
        return asset('assets/images/no-image.png');
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke model Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
