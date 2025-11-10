<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Models\Kategori;

/**
 * SOLID Principles Applied:
 * 
 * Single Responsibility Principle (SRP):
 * - Controller hanya bertanggung jawab untuk HTTP request/response handling
 * - Business logic dipindahkan ke ProductService
 * 
 * Dependency Inversion Principle (DIP):
 * - Controller bergantung pada ProductService abstraction
 * - Menggunakan dependency injection untuk loose coupling
 * 
 * Open/Closed Principle (OCP):
 * - Controller dapat di-extend untuk fitur baru tanpa memodifikasi existing code
 */
class SolidProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display all products
     * Single Responsibility: Only handles HTTP response for products listing
     */
    public function index(Request $request)
    {
        try {
            $categoryId = $request->get('category');
            $search = $request->get('search');
            
            $products = $this->productService->getAllProducts($categoryId, $search);
            $categories = Kategori::all();
            
            return view('user.products', compact('products', 'categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display single product
     * Single Responsibility: Only handles HTTP response for product detail
     */
    public function show($id)
    {
        try {
            $product = $this->productService->findProduct($id);
            
            if (!$product) {
                abort(404, 'Product not found');
            }
            
            return view('user.product-detail', compact('product'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Display products management page
     * Single Responsibility: Only handles HTTP response for admin products view
     */
    public function adminIndex()
    {
        try {
            $products = $this->productService->getPaginatedProducts(10);
            $categories = Kategori::all();
            
            return view('admin.products.index', compact('products', 'categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Store new product
     * Single Responsibility: Only handles HTTP request for product creation
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|numeric|min:0',
            'jumlah_produk' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategoris,id',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $this->productService->createProduct(
                $request->only(['nama_produk', 'harga_produk', 'jumlah_produk', 'id_kategori']),
                $request->file('gambar_produk')
            );

            return redirect()->back()->with('success', 'Product created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Update product
     * Single Responsibility: Only handles HTTP request for product update
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga_produk' => 'required|numeric|min:0',
            'jumlah_produk' => 'required|integer|min:0',
            'id_kategori' => 'required|exists:kategoris,id',
            'gambar_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $updated = $this->productService->updateProduct(
                $id,
                $request->only(['nama_produk', 'harga_produk', 'jumlah_produk', 'id_kategori']),
                $request->file('gambar_produk')
            );

            if (!$updated) {
                return redirect()->back()->with('error', 'Product not found!');
            }

            return redirect()->back()->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Delete product
     * Single Responsibility: Only handles HTTP request for product deletion
     */
    public function destroy($id)
    {
        try {
            $deleted = $this->productService->deleteProduct($id);

            if (!$deleted) {
                return redirect()->back()->with('error', 'Product not found!');
            }

            return redirect()->back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Get low stock products
     * Single Responsibility: Only handles HTTP response for low stock report
     */
    public function lowStock()
    {
        try {
            $lowStockProducts = $this->productService->getLowStockProducts(10);
            
            return view('admin.products.low-stock', compact('lowStockProducts'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}