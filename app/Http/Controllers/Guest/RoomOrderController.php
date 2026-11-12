<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class RoomOrderController extends Controller
{
    public function entry(Request $request): View|RedirectResponse
    {
        if ($this->sessionRoom($request)) {
            return redirect()->route('guest.order.menu');
        }

        return view('guest.order.entry');
    }

    public function open(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_number' => ['required', 'string', 'max:20'],
        ]);

        $room = Room::query()
            ->where('room_number', trim($validated['room_number']))
            ->where('is_active', true)
            ->first();

        if (! $room) {
            return back()
                ->withErrors([
                    'room_number' => 'Nomor kamar tidak ditemukan atau sedang tidak aktif.',
                ])
                ->withInput();
        }

        $request->session()->put('guest_room_id', $room->id);
        $request->session()->put('guest_room_token', $room->qr_token);

        return redirect()->route('guest.order.menu');
    }

    public function menu(Request $request): View|Response|RedirectResponse
    {
        $room = $this->sessionRoom($request);

        if (! $room) {
            return redirect()->route('guest.order.entry');
        }

        return $this->renderMenu($room);
    }

    public function show(Request $request, string $token): View|Response
    {
        $room = strlen($token) === 64
            ? Room::query()
                ->where('qr_token', $token)
                ->where('is_active', true)
                ->first()
            : null;

        if (! $room) {
            return $this->invalidRoom(
                'QR kamar tidak dikenali',
                'Tautan QR ini tidak valid atau sudah diganti. Silakan hubungi resepsionis untuk mendapatkan QR terbaru di kamar Anda.',
            );
        }

        $request->session()->put('guest_room_id', $room->id);
        $request->session()->put('guest_room_token', $room->qr_token);

        return $this->renderMenu($room);
    }

    private function renderMenu(Room $room): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->whereHas('products')
            ->with([
                'products' => function ($query) {
                    $query->orderByDesc('is_available')
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

    private function sessionRoom(Request $request): ?Room
    {
        $roomId = $request->session()->get('guest_room_id');
        $token = $request->session()->get('guest_room_token');

        if (! $roomId || ! $token) {
            return null;
        }

        return Room::query()
            ->whereKey($roomId)
            ->where('qr_token', $token)
            ->where('is_active', true)
            ->first();
    }

    public function confirmation(Request $request, string $orderNumber): View|Response
    {
        $orderId = $request->session()->get('guest_order_id');
        $roomId = $request->session()->get('guest_room_id');

        if (! $orderId || ! $roomId) {
            return $this->invalidRoom(
                'Detail pesanan tidak tersedia',
                'Sesi pesanan tidak ditemukan. Silakan kembali ke menu kamar atau hubungi kasir untuk bantuan.',
                'Kembali ke Beranda',
            );
        }

        $order = Order::query()
            ->with(['items', 'room'])
            ->whereKey($orderId)
            ->where('order_number', $orderNumber)
            ->where('room_id', $roomId)
            ->first();

        if (! $order) {
            return $this->invalidRoom(
                'Pesanan tidak ditemukan',
                'Kami tidak menemukan pesanan pada sesi ini. Silakan periksa kembali atau hubungi kasir.',
            );
        }

        return view('guest.order.confirmation', [
            'order' => $order,
        ]);
    }

    public function status(string $token): View|Response
    {
        $order = strlen($token) === 64
            ? Order::query()
                ->with(['items', 'room'])
                ->where('public_token', $token)
                ->first()
            : null;

        if (! $order) {
            return $this->invalidRoom(
                'Status pesanan tidak ditemukan',
                'Tautan status ini tidak valid atau sudah kedaluwarsa. Pastikan Anda membuka tautan dari halaman konfirmasi pesanan.',
            );
        }

        return view('guest.order.status', [
            'order' => $order,
        ]);
    }

    public function statusData(string $token): JsonResponse
    {
        $order = Order::query()
            ->where('public_token', $token)
            ->first();

        if (! $order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan.'], 404);
        }

        return response()->json([
            'order_status' => $order->order_status,
            'status_label' => $order->order_status_label,
            'payment_status' => $order->payment_status,
            'payment_label' => $order->payment_status_label,
            'step_index' => $order->status_step_index,
            'steps' => $order->status_steps,
            'updated_at' => $order->updated_at?->toIso8601String(),
        ]);
    }

    private function invalidRoom(
        string $heading,
        string $message,
        string $backLabel = 'Kembali ke Beranda'
    ): View|Response {
        return response()->view('guest.order.invalid', [
            'heading' => $heading,
            'message' => $message,
            'backLabel' => $backLabel,
        ], 404);
    }
}
