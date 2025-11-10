<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $kategories = Kategori::all();
        $products = Product::with('kategori')->get();

        return view('admin.products', compact('products', 'kategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(true));

        $kategori = $this->findKategori($data['kategori_produk']);
        if (!$kategori) {
            return back()->with('error', 'Kategori tidak ditemukan.');
        }

        $path = $this->handleImageUpload($request);

        Product::create([
            'id_user'       => Auth::id(),
            'nama_produk'   => $data['nama_produk'],
            'id_kategori'   => $kategori->id,
            'harga_produk'  => $data['harga_produk'],
            'jumlah_produk' => $data['jumlah_produk'],
            'gambar_produk' => $path,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate($this->rules());

        $kategori = $this->findKategori($data['kategori_produk']);
        if (!$kategori) {
            return back()->with('error', 'Kategori tidak ditemukan.');
        }

        if ($request->hasFile('gambar_produk')) {
            $this->deleteImage($product->gambar_produk);
            $product->gambar_produk = $this->handleImageUpload($request);
        }

        $product->update([
            'nama_produk'   => $data['nama_produk'],
            'id_kategori'   => $kategori->id,
            'harga_produk'  => $data['harga_produk'],
            'jumlah_produk' => $data['jumlah_produk'],
            'gambar_produk' => $product->gambar_produk,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $this->deleteImage($product->gambar_produk);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }

    /**
     * Rules validasi form.
     */
    private function rules($isStore = false): array
    {
        return [
            'nama_produk'     => 'required|string|max:255',
            'kategori_produk' => 'required|string',
            'harga_produk'    => 'required|numeric',
            'jumlah_produk'   => 'required|numeric',
            'gambar_produk'   => ($isStore ? 'required' : 'nullable') . '|image|max:2048',
        ];
    }

    /**
     * Cari kategori berdasarkan nama.
     */
    private function findKategori(string $nama)
    {
        return Kategori::where('nama_kategori', $nama)->first();
    }

    /**
     * Upload gambar produk.
     */
    private function handleImageUpload(Request $request): ?string
    {
        return $request->file('gambar_produk')?->store('products', 'public');
    }

    /**
     * Hapus gambar dari storage.
     */
    private function deleteImage(?string $path): void
    {
        if ($path && Storage::exists('public/' . $path)) {
            Storage::delete('public/' . $path);
        }
    }
}
