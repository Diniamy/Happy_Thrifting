<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;

/**
 * SOLID Principles Applied:
 * 
 * Single Responsibility Principle (SRP):
 * - Controller hanya bertanggung jawab untuk HTTP request/response handling
 * - Business logic dipindahkan ke Service classes
 * 
 * Dependency Inversion Principle (DIP):
 * - Controller bergantung pada Service abstraction, bukan concrete models
 * - Menggunakan dependency injection untuk loose coupling
 */
class CartController extends Controller
{
    protected CartService $cartService;
    protected OrderService $orderService;
    protected ProductService $productService;

    public function __construct(CartService $cartService, OrderService $orderService, ProductService $productService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
        $this->productService = $productService;
    }

    /**
     * Display cart items
     * Single Responsibility: Only handles HTTP response for cart view
     */
    public function view()
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You need to log in to view your cart.');
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
     * Single Responsibility: Only handles HTTP request for adding to cart
     */
    public function add($id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You need to log in to add products to cart.');
        }

        try {
            $this->cartService->addToCart(Auth::id(), $id, 1);
            return redirect()->route('user.cart')->with('success', 'Product added to cart!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Buy product directly (without cart)
     * Single Responsibility: Only handles HTTP request for direct purchase
     */
    public function buyNow(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You need to log in to buy a product.');
        }

        $quantity = $request->get('quantity', 1);

        // Validate quantity
        if ($quantity < 1) {
            return redirect()->back()->with('error', 'Quantity must be at least 1.');
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
     * Single Responsibility: Only handles HTTP request for checkout
     */
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You need to be logged in to access checkout.');
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

    public function midtransCallback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        // Validasi signature key dari Midtrans
        $signatureKey = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($signatureKey === $request->signature_key) {
            $order = $this->orderService->getOrder($request->order_id);

            if ($order) {
                // Update status order berdasarkan status transaksi
                if ($request->transaction_status === 'settlement') {
                    $order->update(['status' => 'completed']);
                } elseif ($request->transaction_status === 'pending') {
                    $order->update(['status' => 'pending']);
                } elseif ($request->transaction_status === 'cancel') {
                    $order->update(['status' => 'cancel']);
                }

                // Ambil VA Number dan Bank dari respons Midtrans jika payment_type adalah bank_transfer
                if ($request->payment_type === 'bank_transfer' && isset($request->va_numbers[0])) {
                    $vaNumber = $request->va_numbers[0]['va_number'];
                    $bank = $request->va_numbers[0]['bank'];

                    // Update kolom payment_va_name dan payment_va_number di database
                    $order->update([
                        'payment_name' => $bank,
                        'payment_number' => $vaNumber,
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

        /**
     * Update jumlah produk setelah order
     * Single Responsibility: Delegated to ProductService
     * @param int $id_product ID produk yang dikurangi stoknya. 
     * @param int $jumlah Jumlah produk yang dikurangi.
     */
    private function updateProductQuantity($id_product, $jumlah)
    {
        $this->productService->updateStock($id_product, -$jumlah);
    }

    /**
     * Remove item from cart
     * Single Responsibility: Only handles HTTP request for item removal
     */
    public function delete($id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You need to log in to remove items from cart.');
        }

        try {
            $this->cartService->removeFromCart(Auth::id(), $id);
            return redirect()->route('user.cart')->with('success_delete', 'Product removed from cart!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update cart item quantity
     * Single Responsibility: Only handles HTTP request for quantity update
     */
    public function updateQuantity(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You need to log in to update cart.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $this->cartService->updateQuantity(Auth::id(), $id, $request->quantity);
            return redirect()->route('user.cart')->with('success', 'Cart updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('user.cart')->with('error', $e->getMessage());
        }
    }

    /**
     * Display payment page
     * Single Responsibility: Only handles HTTP response for payment view
     */
    public function payment($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        try {
            $order = $this->orderService->getOrder($orderId);
            
            if (!$order || $order->id_user !== Auth::id()) {
                abort(403, 'Unauthorized access to this order.');
            }

            $banks = Bank::where('is_active', true)->get();
            return view('user.payment', compact('order', 'banks'));
        } catch (\Exception $e) {
            return redirect()->route('user.history')->with('error', $e->getMessage());
        }
    }

    /**
     * Process payment
     * Single Responsibility: Only handles HTTP request for payment processing
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
                abort(403, 'Unauthorized access to this order.');
            }

            // Upload bukti transfer
            $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

            // Update order with payment info
            $order->update([
                'bank_id' => $request->bank_id,
                'bukti_transfer' => $path,
                'status' => 'waiting_confirmation'
            ]);

            return redirect()->route('user.history')->with('success', 'Payment proof uploaded successfully! Please wait for admin confirmation.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
