<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Order;

class RoomOrderController extends Controller
{
    
public function confirmation(
    Request $request,
    string $orderNumber
): View {
    $orderId = $request->session()->get('guest_order_id');
    $roomId = $request->session()->get('guest_room_id');

    abort_unless($orderId && $roomId, 404);

    $order = Order::query()
        ->with(['items', 'room'])
        ->whereKey($orderId)
        ->where('order_number', $orderNumber)
        ->where('room_id', $roomId)
        ->firstOrFail();

    return view('guest.order.confirmation', [
        'order' => $order,
    ]);
}

    public function show(Request $request, string $token): View
    {
        $room = Room::query()
            ->where('qr_token', $token)
            ->where('is_active', true)
            ->firstOrFail();

        $request->session()->put('guest_room_id', $room->id);
        $request->session()->put('guest_room_token', $room->qr_token);

        $categories = Category::query()
            ->where('is_active', true)
            ->whereHas('products', function ($query) {
                $query->where('is_available', true);
            })
            ->with([
                'products' => function ($query) {
                    $query->where('is_available', true)
                        ->orderBy('sort_order')
                        ->orderBy('name');
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('guest.order.index', [
            'room' => $room,
            'categories' => $categories,
        ]);
    }
}
