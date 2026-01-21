<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

/**
 * SOLID Principles Applied:
 * 
 * Single Responsibility Principle (SRP):
 * - Controller hanya bertanggung jawab untuk menangani permintaan dan respons HTTP
 * - Logika bisnis dipindahkan ke OrderService
 * 
 * Dependency Inversion Principle (DIP):
 * - Controller bergantung pada abstraksi OrderService
 * - Menggunakan dependency injection untuk mengurangi ketergantungan langsung (loose coupling)
 */
class SolidOrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Menampilkan riwayat pesanan pengguna
     * Single Responsibility: Hanya menangani respons HTTP untuk riwayat pesanan
     */
    public function history()
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'Anda harus login untuk melihat riwayat pesanan.');
        }

        try {
            $orders = $this->orderService->getUserOrders(Auth::id());
            return view('user.history', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Menampilkan detail pesanan untuk pengguna
     * Single Responsibility: Hanya menangani respons HTTP untuk detail pesanan
     */
    public function orderDetailUser($id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        try {
            $order = $this->orderService->getOrder($id);

            if (!$order || $order->id_user !== Auth::id()) {
                abort(403, 'Akses tidak sah ke pesanan ini.');
            }

            return view('user.order-detail-user', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('user.history')->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Menampilkan semua pesanan
     * Single Responsibility: Hanya menangani respons HTTP untuk tampilan pesanan admin
     */
    public function adminIndex()
    {
        try {
            $orders = $this->orderService->getAllOrders(15);
            return view('admin.orders.index', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Menampilkan detail pesanan
     * Single Responsibility: Hanya menangani respons HTTP untuk detail pesanan admin
     */
    public function adminShow($id)
    {
        try {
            $order = $this->orderService->getOrder($id);

            if (!$order) {
                abort(404, 'Pesanan tidak ditemukan');
            }

            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Memperbarui status pesanan
     * Single Responsibility: Hanya menangani permintaan HTTP untuk memperbarui status pesanan
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,waiting_payment,waiting_confirmation,confirmed,completed,cancelled,processing,shipped',
            'catatan_admin' => 'nullable|string|max:500'
        ]);

        try {
            $order = $this->orderService->getOrder($id);

            if (!$order) {
                return redirect()->back()->with('error', 'Pesanan tidak ditemukan!');
            }

            // Perbarui status pesanan
            $this->orderService->updateOrderStatus($id, $request->status);

            // Perbarui catatan admin jika diisi
            if ($request->catatan_admin) {
                $order->update(['catatan_admin' => $request->catatan_admin]);
            }

            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Mendapatkan pesanan berdasarkan status
     * Single Responsibility: Hanya menangani respons HTTP untuk pesanan berdasarkan status
     */
    public function getByStatus($status)
    {
        try {
            $orders = $this->orderService->getOrdersByStatus($status);
            return view('admin.orders.by-status', compact('orders', 'status'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * API: Mendapatkan statistik pesanan
     * Single Responsibility: Hanya menangani respons HTTP untuk statistik pesanan
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_orders' => $this->orderService->getAllOrders(999999)->count(),
                'pending_orders' => $this->orderService->getOrdersByStatus('pending')->count(),
                'completed_orders' => $this->orderService->getOrdersByStatus('completed')->count(),
                'cancelled_orders' => $this->orderService->getOrdersByStatus('cancelled')->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
