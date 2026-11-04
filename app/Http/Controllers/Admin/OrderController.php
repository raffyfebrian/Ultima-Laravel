<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['room', 'items']);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(15);

        $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];
        $paymentStatuses = ['unpaid', 'paid', 'refunded'];

        return view('admin.orders.index', compact('orders', 'statuses', 'paymentStatuses'));
    }

    public function show(Order $order)
    {
        $order->load(['room', 'items']);

        $statuses = ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];

        return view('admin.orders.show', compact('order', 'statuses'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,confirmed,preparing,ready,completed,cancelled',
        ]);

        $order->update([
            'order_status' => $request->order_status,
        ]);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
