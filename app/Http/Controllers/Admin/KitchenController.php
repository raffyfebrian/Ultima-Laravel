<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;

class KitchenController extends Controller
{
    public function index()
    {
        $orders = Order::with(['room', 'items'])
            ->whereIn('order_status', ['pending', 'confirmed', 'preparing'])
            ->latest()
            ->paginate(15);

        $preparingCount = Order::where('order_status', 'preparing')->count();
        $readyCount = Order::where('order_status', 'ready')->count();

        return view('admin.kitchen.index', compact('orders', 'preparingCount', 'readyCount'));
    }
}
