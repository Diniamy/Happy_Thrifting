<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori'; // Nama tabel

    protected $fillable = [
        'nama_kategori',
        'gambar_kategori',
    ];

    protected $appends = ['gambar_url'];

    // Accessor untuk mendapatkan URL gambar yang benar
    public function getGambarUrlAttribute()
    {
        if ($this->gambar_kategori) {
            // Cek apakah file ada di storage
            if (Storage::disk('public')->exists($this->gambar_kategori)) {
                return asset('storage/' . $this->gambar_kategori);
            }
        }
        // Return gambar default jika tidak ada
        return asset('assets/images/no-image.png');
    }

    // Relasi dengan model Produk
    public function products()
    {
        return $this->hasMany(Product::class, 'kategori_produk', 'nama_kategori');
    }
}
