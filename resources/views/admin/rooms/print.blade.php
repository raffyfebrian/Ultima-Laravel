<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cetak QR Kamar — Ngoffe ID</title>
        <style>
            :root {
                color-scheme: light;
                --ink: #0f172a;
                --muted: #64748b;
                --line: #cbd5e1;
                --teal: #0f766e;
            }

            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 24px;
                background: #f1f5f9;
                color: var(--ink);
                font-family: "Inter", ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            }

            .toolbar {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                max-width: 210mm;
                margin: 0 auto 18px;
                padding: 16px 18px;
                border-radius: 16px;
                background: #ffffff;
                border: 1px solid #e2e8f0;
                box-shadow: 0 18px 40px -30px rgba(15, 23, 42, 0.5);
            }

            .toolbar h1 {
                margin: 0;
                font-size: 18px;
            }

            .toolbar p {
                margin: 4px 0 0;
                color: var(--muted);
                font-size: 13px;
            }

            .toolbar-actions {
                display: flex;
                gap: 8px;
            }

            .button {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 10px 16px;
                border-radius: 10px;
                border: 1px solid #e2e8f0;
                background: #ffffff;
                color: var(--ink);
                font-size: 13px;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
            }

            .button--primary {
                border-color: transparent;
                background: linear-gradient(135deg, #14b8a6, #059669);
                color: #ffffff;
            }

            .sheet {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 8mm;
                max-width: 210mm;
                margin: 0 auto;
            }

            .label-card {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 3mm;
                padding: 6mm 5mm;
                border: 1px dashed var(--line);
                border-radius: 4mm;
                background: #ffffff;
                text-align: center;
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .brand {
                display: inline-flex;
                align-items: center;
                gap: 2mm;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 0.14em;
                text-transform: uppercase;
                color: var(--teal);
            }

            .brand-mark {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 6mm;
                height: 6mm;
                border-radius: 1.6mm;
                background: var(--teal);
                color: #ffffff;
                font-size: 10px;
                letter-spacing: 0;
            }

            .room-number {
                margin: 0;
                font-size: 21px;
                font-weight: 800;
                letter-spacing: -0.02em;
                line-height: 1;
            }

            .room-number span {
                display: block;
                margin-bottom: 1.5mm;
                font-size: 8px;
                font-weight: 600;
                letter-spacing: 0.16em;
                text-transform: uppercase;
                color: var(--muted);
            }

            .qr {
                width: 44mm;
                height: 44mm;
                padding: 2mm;
                border: 1px solid #e2e8f0;
                border-radius: 3mm;
                background: #ffffff;
            }

            .qr img {
                display: block;
                width: 100%;
                height: 100%;
            }

            .instruction {
                margin: 0;
                max-width: 48mm;
                font-size: 9.5px;
                line-height: 1.45;
                color: var(--muted);
            }

            .instruction strong {
                color: var(--ink);
            }

            .empty {
                max-width: 210mm;
                margin: 0 auto;
                padding: 48px 24px;
                border-radius: 16px;
                border: 1px dashed var(--line);
                background: #ffffff;
                text-align: center;
                color: var(--muted);
            }

            @page {
                size: A4;
                margin: 10mm;
            }

            @media (max-width: 720px) {
                .sheet {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (max-width: 460px) {
                .sheet {
                    grid-template-columns: minmax(0, 1fr);
                }
            }

            @media print {
                body {
                    padding: 0;
                    background: #ffffff;
                }

                .toolbar {
                    display: none;
                }

                .sheet {
                    max-width: none;
                }

                .label-card {
                    border-color: #94a3b8;
                }
            }
        </style>
    </head>
    <body>
        <div class="toolbar">
            <div>
                <h1>Label QR Kamar</h1>
                <p>
                    {{ $rooms->count() }} label
                    @if ($search !== '')
                        · pencarian "{{ $search }}"
                    @endif
                    · cetak pada kertas A4, lalu potong mengikuti garis putus-putus.
                </p>
            </div>
            <div class="toolbar-actions">
                <button type="button" class="button button--primary" onclick="window.print()">
                    Cetak sekarang
                </button>
                <a href="{{ route('admin.rooms.index') }}" class="button">Kembali</a>
            </div>
        </div>

        @if ($rooms->isEmpty())
            <div class="empty">
                <p><strong>Tidak ada kamar aktif untuk dicetak.</strong></p>
                <p>Aktifkan kamar terlebih dahulu, atau pilih satu kamar dari halaman Kamar &amp; QR.</p>
            </div>
        @else
            <div class="sheet">
                @foreach ($rooms as $room)
                    <div class="label-card">
                        <span class="brand">
                            <span class="brand-mark" aria-hidden="true">N</span>
                            Ngoffe ID
                        </span>

                        <p class="room-number">
                            <span>Nomor kamar</span>
                            {{ $room->room_number }}
                        </p>

                        <div class="qr">
                            <img
                                src="{{ route('admin.rooms.qr', $room) }}?v={{ substr($room->qr_token, 0, 10) }}"
                                alt="QR menu Kamar {{ $room->room_number }}"
                                width="480"
                                height="480"
                            >
                        </div>

                        <p class="instruction">
                            <strong>Pindai untuk melihat menu &amp; memesan.</strong><br>
                            Buka kamera ponsel, arahkan ke QR, lalu pilih menu tanpa perlu memasang aplikasi.
                        </p>
                    </div>
                @endforeach
            </div>
        @endif
    </body>
</html>
