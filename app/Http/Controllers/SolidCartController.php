<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;

/**
 * SOLID Principles Applied:
 * 
 * Single Responsibility Principle (SRP):
 * - Controller hanya bertanggung jawab untuk menangani permintaan (request) dan tanggapan (response) HTTP
 * - Logika bisnis dipindahkan ke Service classes agar lebih terstruktur
 *  
 * Dependency Inversion Principle (DIP):
 * - Controller bergantung pada Service abstraction, bukan langsung ke model
 * - Menggunakan dependency injection agar hubungan antar komponen menjadi longgar (loose coupling)
 * 
 * Open/Closed Principle (OCP):
 * - Controller terbuka untuk penambahan fitur baru, tetapi tertutup untuk modifikasi langsung
 */
class SolidCartController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    /**
     * Display cart items
     * Single Responsibility: Hanya menangani respons HTTP untuk menampilkan tampilan keranjang
     */
    public function view()
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu untuk melihat keranjang.');
        }

        try {
            $carts = $this->cartService->getCartItems(Auth::id());
            return view('user.cart', compact('carts'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Add product to cart
     * Single Responsibility: Hanya menangani permintaan HTTP untuk menambahkan produk ke keranjang
     */
    public function add($id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu untuk menambahkan produk ke keranjang.');
        }

        try {
            $this->cartService->addToCart(Auth::id(), $id, 1);
            return redirect()->route('user.cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Buy product directly (without cart)
     * Single Responsibility: Hanya menangani permintaan HTTP untuk pembelian langsung tanpa masuk keranjang
     */
    public function buyNow(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu untuk membeli produk.');
        }

        $quantity = $request->get('quantity', 1);

        // Validate quantity
        if ($quantity < 1) {
            return redirect()->back()->with('error', 'Jumlah produk minimal 1.');
        }

        try {
            $order = $this->orderService->createDirectOrder(Auth::id(), $id, $quantity);
            return redirect()->route('user.payment', $order->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Checkout cart items
     * Single Responsibility: Hanya menangani permintaan HTTP untuk proses checkout keranjang
     */
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Silakan login untuk melanjutkan ke proses checkout.');
        }

        try {
            // Validate cart stock before checkout
            $stockErrors = $this->cartService->validateCartStock(Auth::id());
            if (!empty($stockErrors)) {
                return redirect()->route('user.cart')->with('error', implode(', ', $stockErrors));
            }

            $order = $this->orderService->createOrderFromCart(Auth::id());
            return redirect()->route('user.payment', $order->id);
        } catch (\Exception $e) {
            return redirect()->route('user.cart')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove item from cart
     * Single Responsibility: Hanya menangani permintaan HTTP untuk menghapus produk dari keranjang
     */
    public function delete($id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Silakan login untuk menghapus produk dari keranjang.');
        }

        try {
            $this->cartService->removeFromCart(Auth::id(), $id);
            return redirect()->route('user.cart')->with('success_delete', 'Produk berhasil dihapus dari keranjang!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     * Single Responsibility: Hanya menangani permintaan HTTP untuk memperbarui jumlah produk di keranjang
     */
    public function updateQuantity(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Silakan login untuk memperbarui keranjang.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->cartService->updateQuantity(Auth::id(), $id, $request->quantity);
            return redirect()->route('user.cart')->with('success', 'Keranjang berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('user.cart')->with('error', $e->getMessage());
        }
    }

    /**
     * Display payment page
     * Single Responsibility: Hanya menangani tampilan halaman pembayaran untuk user
     */
    public function payment($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        try {
            $order = $this->orderService->getOrder($orderId);

            if (!$order || $order->id_user !== Auth::id()) {
                abort(403, 'Akses tidak sah terhadap pesanan ini.');
            }

            $banks = Bank::where('is_active', true)->get();
            return view('user.payment', compact('order', 'banks'));
        } catch (\Exception $e) {
            return redirect()->route('user.history')->with('error', $e->getMessage());
        }
    }

    /**
     * Process payment
     * Single Responsibility: Hanya menangani proses HTTP untuk mengunggah dan memproses bukti pembayaran
     */
    public function processPayment(Request $request, $orderId)
    {
        $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $order = $this->orderService->getOrder($orderId);

            if (!$order || $order->id_user !== Auth::id()) {
                abort(403, 'Akses tidak sah terhadap pesanan ini.');
            }

            // Upload payment proof
            $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

            // Update order data with payment information
            $order->update([
                'bank_id' => $request->bank_id,
                'bukti_transfer' => $path,
                'status' => 'waiting_confirmation'
            ]);

            return redirect()->route('user.history')->with('success', 'Bukti pembayaran berhasil diunggah! Silakan tunggu konfirmasi dari admin.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

