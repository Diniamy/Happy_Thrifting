<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil semua kategori
        $categories = Kategori::all();

        // Menyaring produk berdasarkan kategori jika ada parameter kategori
        $kategori_id = $request->get('kategori', null);

        // Jika kategori_id ada, ambil produk berdasarkan kategori
        if ($kategori_id) {
            $products = Product::where('id_kategori', $kategori_id)->where('jumlah_produk', '>', 0)->get();
        } else {
            // Jika kategori_id tidak ada, ambil semua produk
            $products = Product::where('jumlah_produk', '>', 0)->get();
        }

        // Kembalikan view dengan data kategori dan produk
        return view('index', compact('products', 'categories'));
    }


    public function about()
    {
        return view('about');
    }

    public function product_page(Request $request)
    {
        // Mengambil semua kategori
        $categories = Kategori::all();

        // Memeriksa apakah ada kategori yang dipilih
        $kategori_id = $request->get('kategori', null);

        // Menampilkan produk berdasarkan kategori yang dipilih
        if ($kategori_id) {
            $products = Product::where('id_kategori', $kategori_id)->where('jumlah_produk', '>', 0)->get();
        } else {
            // Menampilkan semua produk jika tidak ada kategori yang dipilih
            $products = Product::where('jumlah_produk', '>', 0)->get();
        }

        return view('product_page', compact('products', 'categories'));
    }
}
