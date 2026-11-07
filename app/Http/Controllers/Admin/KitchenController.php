<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['room', 'items'])
            ->whereIn('order_status', ['pending', 'confirmed', 'preparing', 'ready']);

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->orderBy('placed_at')->paginate(12)->withQueryString();

        $pendingCount = Order::whereIn('order_status', ['pending', 'confirmed'])->count();
        $preparingCount = Order::where('order_status', 'preparing')->count();
        $readyCount = Order::where('order_status', 'ready')->count();
        $completedToday = Order::where('order_status', 'completed')
            ->whereDate('updated_at', today())
            ->count();

        return view('admin.kitchen.index', compact(
            'orders',
            'pendingCount',
            'preparingCount',
            'readyCount',
            'completedToday',
        ));
    }

    public function advance(Order $order): RedirectResponse
    {
        $next = match ($order->order_status) {
            'pending' => 'confirmed',
            'confirmed' => 'preparing',
            'preparing' => 'ready',
            'ready' => 'completed',
            default => null,
        };

        if ($next === null) {
            return back()->with('info', 'Status pesanan ini tidak dapat dilanjutkan.');
        }

        $order->update(['order_status' => $next]);

        return back()->with(
            'success',
            'Pesanan '.$order->order_number.' diperbarui menjadi '.$order->fresh()->order_status_label.'.',
        );
    }
}
