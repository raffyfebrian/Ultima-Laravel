<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\QrCodeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct(private readonly QrCodeService $qrCodes) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $status = (string) $request->query('status', '');

        $rooms = Room::query()
            ->when($search !== '', fn ($query) => $query->where('room_number', 'like', '%'.$search.'%'))
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderByRaw('CAST(room_number AS INTEGER)')
            ->orderBy('room_number')
            ->paginate(18)
            ->withQueryString();

        return view('admin.rooms.index', [
            'rooms' => $rooms,
            'search' => $search,
            'status' => $status,
            'totalRooms' => Room::count(),
            'activeRooms' => Room::where('is_active', true)->count(),
        ]);
    }

    public function qr(Room $room): Response
    {
        return response($this->qrCodes->svg($this->guestUrl($room), 480), 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Cache-Control' => 'private, max-age=86400',
        ]);
    }

    public function download(Room $room): Response
    {
        $filename = 'qr-kamar-'.preg_replace('/[^0-9A-Za-z_-]/', '', $room->room_number).'.svg';

        return response($this->qrCodes->svg($this->guestUrl($room), 1024), 200, [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function print(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $roomId = $request->integer('room');

        $rooms = Room::query()
            ->when($roomId > 0, fn ($query) => $query->whereKey($roomId))
            ->when($roomId <= 0, fn ($query) => $query->where('is_active', true))
            ->when($search !== '', fn ($query) => $query->where('room_number', 'like', '%'.$search.'%'))
            ->orderByRaw('CAST(room_number AS INTEGER)')
            ->orderBy('room_number')
            ->get();

        return view('admin.rooms.print', [
            'rooms' => $rooms,
            'search' => $search,
        ]);
    }

    public function toggle(Room $room): RedirectResponse
    {
        $room->update(['is_active' => ! $room->is_active]);

        return back()->with(
            'success',
            'Kamar '.$room->room_number.' '.($room->is_active ? 'diaktifkan' : 'dinonaktifkan').'.',
        );
    }

    public function regenerate(Room $room): RedirectResponse
    {
        $room->update(['qr_token' => Str::random(64)]);

        return back()->with(
            'success',
            'QR Kamar '.$room->room_number.' berhasil dibuat ulang. QR lama tidak berlaku lagi.',
        );
    }

    private function guestUrl(Room $room): string
    {
        // Mengikuti host request saat ini agar QR tetap benar di localhost
        // maupun domain produksi. APP_URL tetap dipakai untuk tautan lain.
        return url(route('guest.order.show', ['token' => $room->qr_token], false));
    }
}
