<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{

    public function index()
    {
        $kategories = Kategori::all();

        return view('admin.kategori', compact('kategories'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'gambar_kategori' => 'required|image|max:2048', // Max 2MB
        ]);

        // Simpan file gambar ke storage
        $path = $request->file('gambar_kategori')->store('kategori', 'public');

        // Buat produk baru
        Kategori::create([
            'id_user' => Auth::id(), // Ambil ID user saat ini
            'nama_kategori' => $request->nama_kategori,
            'gambar_kategori' => $path,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function destroy(Kategori $kategori)
    {
        // Hapus gambar dari storage jika ada
        if ($kategori->gambar_kategori && Storage::exists('public/' . $kategori->gambar_kategori)) {
            Storage::delete('public/' . $kategori->gambar_kategori);
        }

        // Hapus produk
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }

    public function update(Request $request, Kategori $kategori)
    {
        // Validasi data
        $request->validate([
            'nama_kategori' => 'required|string|max:255',

            'gambar_kategori' => 'nullable|image|max:2048', // Opsional jika gambar tidak diubah
        ]);

        // Jika gambar diupload, hapus gambar lama dan simpan yang baru
        if ($request->hasFile('gambar_kategori')) {
            // Hapus gambar lama jika ada
            if ($kategori->gambar_kategori && Storage::exists('public/' . $kategori->gambar_kategori)) {
                Storage::delete('public/' . $kategori->gambar_kategori);
            }

            // Simpan gambar baru
            $path = $request->file('gambar_kategori')->store('kategori', 'public');
            $kategori->gambar_kategori = $path;
        }

        // Perbarui data produk
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,

            'gambar_kategori' => $kategori->gambar_kategori,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }
}
