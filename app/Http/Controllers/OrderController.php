<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;

class OrderController extends Controller
{
    public function order()
    {
        // Ambil cart sesuai dengan user_id yang sedang login
        $carts = Cart::with('products')->where('id_user', Auth::id())->get();

        // dd($carts);

        // Hitung total harga dari keranjang
        $total_harga = 0;
        foreach ($carts as $cart) {

            $total_harga += $cart->products->harga_produk * $cart->jumlah;
        }

        return view('user.order', compact('carts', 'total_harga'));
    }


    public function history()
    {
        // Ambil orders dengan relasi yang diperlukan dan urutkan berdasarkan tanggal terbaru
        $orders = Order::with(['items.products', 'user'])
                      ->where('id_user', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('user.history', compact('orders'));
    }

    public function order_detail_user(Order $order)
    {
        // Pastikan order ini milik user yang sedang login
        if ($order->id_user !== Auth::id()) {
            abort(403, 'Unauthorized access to this order.');
        }

        // Load relasi yang diperlukan
        $order->load(['items.products', 'user']);

        return view('user.order-detail-user', compact('order'));
    }

    public function index()
    {
        // Hanya tampilkan order yang belum selesai dan belum dibatalkan
        $orders = Order::with(['user'])
                      ->whereNotIn('status', ['confirmed', 'completed', 'cancelled'])
                      ->orderBy('created_at', 'desc')
                      ->get();
        return view('admin.orders', compact('orders'));
    }


    public function destroy(Order $order)
    {


        // Hapus produk
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order berhasil dihapus!');
    }


    public function detail_order(Order $order)
    {
        // Ambil semua item dalam order ini
        $items = $order->items()->with('products')->get();

        return view('admin.order-detail', compact('order', 'items'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $order->update([
            'status' => 'confirmed',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->route('admin.order-detail', $order->id)
                        ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000'
        ]);

        $order->update([
            'status' => 'cancelled',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->route('admin.order-detail', $order->id)
                        ->with('success', 'Pembayaran berhasil ditolak!');
    }

    public function reports(Request $request)
    {
        $query = Order::with(['user', 'items.products', 'bank'])
                      ->whereIn('status', ['confirmed', 'completed']);

        // Apply date filters if provided - menggunakan created_at untuk filter tanggal order
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $completedOrders = $query->orderBy('created_at', 'desc')->get();

        return view('admin.reports', compact('completedOrders'));
    }

    public function cancelledOrders()
    {
        $cancelledOrders = Order::with(['user', 'items.products', 'bank'])
                                ->where('status', 'cancelled')
                                ->orderBy('updated_at', 'desc')
                                ->get();

        return view('admin.cancelled-orders', compact('cancelledOrders'));
    }
}
