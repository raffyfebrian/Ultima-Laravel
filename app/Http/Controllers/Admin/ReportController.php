<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to] = $this->range($request);
        $report = $this->build($from, $to);

        return view('admin.reports.index', array_merge($report, compact('from', 'to')));
    }

    public function export(Request $request): StreamedResponse
    {
        [$from, $to] = $this->range($request);
        $report = $this->build($from, $to);

        $filename = 'laporan-penjualan-'.$from->format('Ymd').'-'.$to->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($report) {
            $handle = fopen('php://output', 'w');

            // BOM agar karakter Indonesia terbaca rapi di Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No. Pesanan',
                'Tanggal Bayar',
                'Kamar',
                'Jumlah Item',
                'Subtotal',
                'Total',
                'Metode Pembayaran',
                'Status Pembayaran',
            ]);

            foreach ($report['orders'] as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->payment_confirmed_at?->format('d/m/Y H:i'),
                    $order->room?->room_number ?? '-',
                    $order->items->sum('quantity'),
                    (float) $order->subtotal,
                    (float) $order->total,
                    $order->payment_method_label,
                    $order->payment_status_label,
                ]);
            }

            fputcsv($handle, []);
            fputcsv($handle, ['RINGKASAN']);
            fputcsv($handle, ['Total Pendapatan', '', '', $report['itemsSold'], '', $report['revenue'], '', '']);
            fputcsv($handle, ['Jumlah Transaksi', '', '', '', '', $report['orderCount'], '', '']);
            fputcsv($handle, ['Rata-rata per Transaksi', '', '', '', '', round($report['average'], 2), '', '']);

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function range(Request $request): array
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
        ]);

        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : now()->endOfMonth();

        if ($from->gt($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $to];
    }

    /**
     * @return array<string, mixed>
     */
    private function build(Carbon $from, Carbon $to): array
    {
        $orders = Order::query()
            ->with(['room', 'items'])
            ->where('payment_status', 'paid')
            ->whereBetween('payment_confirmed_at', [$from, $to])
            ->orderBy('payment_confirmed_at')
            ->get();

        $revenue = (float) $orders->sum('total');
        $orderCount = $orders->count();
        $itemsSold = (int) $orders->flatMap->items->sum('quantity');

        $daily = $orders
            ->groupBy(fn (Order $order) => $order->payment_confirmed_at->format('Y-m-d'))
            ->map(fn ($group, $date) => [
                'date' => Carbon::parse($date),
                'orders' => $group->count(),
                'items' => (int) $group->flatMap->items->sum('quantity'),
                'revenue' => (float) $group->sum('total'),
            ])
            ->sortKeys()
            ->values();

        $topProducts = $orders
            ->flatMap->items
            ->groupBy('product_name')
            ->map(fn ($items, $name) => [
                'name' => $name,
                'quantity' => (int) $items->sum('quantity'),
                'revenue' => (float) $items->sum('subtotal'),
            ])
            ->sortByDesc('quantity')
            ->take(8)
            ->values();

        $paymentBreakdown = $orders
            ->groupBy(fn (Order $order) => $order->payment_method ?: 'unknown')
            ->map(fn ($group, $method) => [
                'method' => $method,
                'label' => match ($method) {
                    'cash' => 'Tunai',
                    'qris' => 'QRIS',
                    'transfer' => 'Transfer',
                    'card' => 'Kartu',
                    default => 'Belum Ditentukan',
                },
                'orders' => $group->count(),
                'revenue' => (float) $group->sum('total'),
            ])
            ->sortByDesc('revenue')
            ->values();

        return [
            'orders' => $orders,
            'revenue' => $revenue,
            'orderCount' => $orderCount,
            'itemsSold' => $itemsSold,
            'average' => $orderCount > 0 ? $revenue / $orderCount : 0.0,
            'daily' => $daily,
            'topProducts' => $topProducts,
            'paymentBreakdown' => $paymentBreakdown,
        ];
    }
}
