<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $order->order_number }}</title>

    <style>
        :root {
            --ink: #111827;
            --muted: #6b7280;
            --line: #d1d5db;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px 12px 48px;
            background: #f3f4f6;
            color: var(--ink);
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .receipt {
            width: 80mm;
            margin: 0 auto;
            padding: 8mm 6mm;
            background: #ffffff;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.12);
            border-radius: 4px;
            font-family: "Courier New", Courier, monospace;
            font-size: 11.5px;
            line-height: 1.5;
        }

        .brand {
            text-align: center;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .brand-name {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.18em;
        }

        .brand-sub {
            margin: 2px 0 0;
            font-size: 9px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .divider {
            margin: 10px 0;
            border-top: 1px dashed var(--line);
        }

        .meta {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 2px 8px;
            font-size: 11px;
        }

        .meta span:nth-child(odd) {
            color: var(--muted);
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .items th {
            padding-bottom: 4px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            text-align: left;
            border-bottom: 1px dashed var(--line);
        }

        .items th:last-child,
        .items td:last-child {
            text-align: right;
        }

        .items td {
            padding: 5px 0;
            vertical-align: top;
        }

        .item-name {
            font-weight: 700;
        }

        .item-detail {
            display: block;
            font-size: 9.5px;
            color: var(--muted);
        }

        .totals {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
        }

        .totals td {
            padding: 3px 0;
        }

        .totals td:last-child {
            text-align: right;
            white-space: nowrap;
        }

        .totals .grand td {
            padding-top: 6px;
            font-size: 14px;
            font-weight: 700;
            border-top: 1px dashed var(--line);
        }

        .badge {
            display: inline-block;
            padding: 1px 8px;
            border: 1px solid currentColor;
            border-radius: 999px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .badge-paid {
            color: #047857;
        }

        .badge-unpaid {
            color: #b45309;
        }

        .badge-refunded {
            color: #475569;
        }

        .footer {
            margin-top: 12px;
            text-align: center;
            font-size: 10px;
            color: var(--muted);
        }

        .footer strong {
            display: block;
            margin-bottom: 3px;
            font-size: 11px;
            color: var(--ink);
        }

        .toolbar {
            width: 80mm;
            margin: 0 auto 16px;
            display: flex;
            gap: 8px;
        }

        .toolbar button,
        .toolbar a {
            flex: 1;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #374151;
            font-size: 13px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
        }

        .toolbar .primary {
            border-color: transparent;
            background: #0f766e;
            color: #ffffff;
        }

        @page {
            size: 80mm auto;
            margin: 3mm;
        }

        @media print {
            body {
                padding: 0;
                background: #ffffff;
            }

            .receipt {
                width: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border-radius: 0;
                font-size: 10.5px;
            }

            .toolbar {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="{{ route('admin.orders.show', $order) }}">&larr; Kembali</a>
        <button type="button" class="primary" onclick="window.print()">Cetak Struk</button>
    </div>

    <div class="receipt">
        <div class="brand">
            <p class="brand-name">NGOFFE</p>
            <p class="brand-sub">Coffee &amp; Eatery</p>
        </div>

        <div class="divider"></div>

        <div class="meta">
            <span>No. Pesanan</span>
            <span>{{ $order->order_number }}</span>

            <span>Tanggal</span>
            <span>{{ ($order->payment_confirmed_at ?? $order->placed_at)?->format('d/m/Y H:i') }}</span>

            <span>Kamar</span>
            <span>{{ $order->room?->room_number ?? '-' }}</span>

            <span>Pembayaran</span>
            <span>{{ $order->payment_method_label }}</span>

            <span>Status</span>
            <span>
                @php
                    $badgeClass = match ($order->payment_status) {
                        'paid' => 'badge-paid',
                        'refunded' => 'badge-refunded',
                        default => 'badge-unpaid',
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $order->payment_status_label }}</span>
            </span>
        </div>

        <div class="divider"></div>

        <table class="items">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <span class="item-name">{{ $item->product_name }}</span>
                            <span class="item-detail">
                                {{ $item->quantity }} x Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider"></div>

        <table class="totals">
            <tr>
                <td>Subtotal</td>
                <td>Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Jumlah Item</td>
                <td>{{ $order->items->sum('quantity') }} pcs</td>
            </tr>
            <tr class="grand">
                <td>Total</td>
                <td>Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="footer">
            <strong>Terima kasih atas kunjungan Anda</strong>
            Simpan struk ini sebagai bukti pembayaran.<br>
            Ngoffe ID &middot; {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <script>
        (function () {
            const params = new URLSearchParams(window.location.search);

            if (params.get('print') === '1') {
                window.addEventListener('load', function () {
                    window.print();
                });
            }
        })();
    </script>
</body>
</html>
