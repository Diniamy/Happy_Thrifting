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
 * - Controller hanya bertanggung jawab untuk HTTP request/response handling
 * - Business logic dipindahkan ke Service classes
 * 
 * Dependency Inversion Principle (DIP):
 * - Controller bergantung pada Service abstraction, bukan concrete models
 * - Menggunakan dependency injection untuk loose coupling
 * 
 * Open/Closed Principle (OCP):
 * - Controller terbuka untuk extension (new methods) tapi tertutup untuk modification
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