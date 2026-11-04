<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
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

        $orders = $query->latest()->paginate(15);

        $totalSales = Order::where('payment_status', 'paid')->sum('total');
        $pendingPayments = Order::where('payment_status', 'unpaid')->count();
        $completedToday = Order::where('payment_status', 'paid')
            ->whereDate('placed_at', today())
            ->count();

        $paymentMethods = Order::distinct()->pluck('payment_method')->filter();

        return view('admin.cashier.index', compact(
            'orders',
            'totalSales',
            'pendingPayments',
            'completedToday',
            'paymentMethods'
        ));
    }
}
