<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

/**
 * SOLID Principles Applied:
 * 
 * Single Responsibility Principle (SRP):
 * - Controller hanya bertanggung jawab untuk HTTP request/response handling
 * - Business logic dipindahkan ke OrderService
 * 
 * Dependency Inversion Principle (DIP):
 * - Controller bergantung pada OrderService abstraction
 * - Menggunakan dependency injection untuk loose coupling
 */
class SolidOrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display user order history
     * Single Responsibility: Only handles HTTP response for order history
     */
    public function history()
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('error', 'You need to log in to view order history.');
        }

        try {
            $orders = $this->orderService->getUserOrders(Auth::id());
            return view('user.history', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display order details for user
     * Single Responsibility: Only handles HTTP response for order detail
     */
    public function orderDetailUser($id)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login');
        }

        try {
            $order = $this->orderService->getOrder($id);

            if (!$order || $order->id_user !== Auth::id()) {
                abort(403, 'Unauthorized access to this order.');
            }

            return view('user.order-detail-user', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('user.history')->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Display all orders
     * Single Responsibility: Only handles HTTP response for admin orders view
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
     * Admin: Display order details
     * Single Responsibility: Only handles HTTP response for admin order detail
     */
    public function adminShow($id)
    {
        try {
            $order = $this->orderService->getOrder($id);

            if (!$order) {
                abort(404, 'Order not found');
            }

            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Update order status
     * Single Responsibility: Only handles HTTP request for status update
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
                return redirect()->back()->with('error', 'Order not found!');
            }

            // Update status
            $this->orderService->updateOrderStatus($id, $request->status);

            // Update admin notes if provided
            if ($request->catatan_admin) {
                $order->update(['catatan_admin' => $request->catatan_admin]);
            }

            return redirect()->back()->with('success', 'Order status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: Get orders by status
     * Single Responsibility: Only handles HTTP response for filtered orders
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
     * API: Get order statistics
     * Single Responsibility: Only handles HTTP response for order statistics
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