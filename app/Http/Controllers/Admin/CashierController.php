<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['room', 'items']);

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('q')) {
            $query->where('order_number', 'like', '%'.$request->q.'%');
        }

        $orders = $query->latest('placed_at')->paginate(15)->withQueryString();

        $totalSales = (float) Order::where('payment_status', 'paid')->sum('total');
        $todaySales = (float) Order::where('payment_status', 'paid')
            ->whereDate('payment_confirmed_at', today())
            ->sum('total');
        $pendingPayments = Order::where('payment_status', 'unpaid')->count();
        $unpaidTotal = (float) Order::where('payment_status', 'unpaid')->sum('total');
        $completedToday = Order::where('payment_status', 'paid')
            ->whereDate('payment_confirmed_at', today())
            ->count();

        $paymentMethods = Order::query()
            ->whereNotNull('payment_method')
            ->distinct()
            ->pluck('payment_method');

        return view('admin.cashier.index', compact(
            'orders',
            'totalSales',
            'todaySales',
            'pendingPayments',
            'unpaidTotal',
            'completedToday',
            'paymentMethods',
        ));
    }

    public function confirmPayment(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'payment_method' => 'nullable|in:cash,qris,transfer,card',
        ]);

        if ($order->payment_status === 'paid') {
            return back()->with('info', 'Pesanan ini sudah lunas.');
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_method' => $request->filled('payment_method')
                ? $request->payment_method
                : ($order->payment_method ?: 'cash'),
            'payment_confirmed_at' => now(),
        ]);

        if (in_array($order->order_status, ['pending', 'cancelled'], true)) {
            $order->update(['order_status' => 'confirmed']);
        }

        return back()->with(
            'success',
            'Pembayaran '.$order->order_number.' berhasil dikonfirmasi.',
        );
    }

    public function refund(Order $order): RedirectResponse
    {
        if ($order->payment_status !== 'paid') {
            return back()->with('info', 'Hanya pembayaran lunas yang dapat dikembalikan.');
        }

        $order->update([
            'payment_status' => 'refunded',
            'payment_confirmed_at' => null,
        ]);

        return back()->with(
            'success',
            'Pembayaran '.$order->order_number.' ditandai sebagai dikembalikan.',
        );
    }
}
