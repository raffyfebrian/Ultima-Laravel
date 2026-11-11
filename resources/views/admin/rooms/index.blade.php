<x-layouts::app title="Kamar & QR — Ngoffe ID">
    <main class="min-h-screen bg-[#f8fafc] px-4 py-8 sm:px-6">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Header --}}
            <header class="dashboard-enter relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-teal-900 p-6 text-white shadow-2xl shadow-teal-900/20 md:p-8">
                <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-teal-400/20 blur-3xl"></div>
                <div class="absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-400/10 blur-3xl"></div>
                <div class="absolute inset-x-8 bottom-0 h-px bg-gradient-to-r from-transparent via-ngofee-mint/40 to-transparent"></div>

                <div class="relative flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-300">Operasional Kamar</p>
                        <h1 class="mt-2 text-3xl font-bold tracking-tight">Kamar &amp; QR Menu</h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-300">
                            Setiap kamar memiliki QR unik. Unduh atau cetak QR, lalu tempatkan di kamar
                            agar tamu bisa membuka menu dan memesan tanpa memasang aplikasi.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.rooms.print', array_filter(['q' => $search])) }}"
                           target="_blank"
                           rel="noopener"
                           class="pressable inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-900 hover:bg-teal-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 9V4h12v5M6 18H4.5A1.5 1.5 0 0 1 3 16.5v-6A1.5 1.5 0 0 1 4.5 9h15A1.5 1.5 0 0 1 21 10.5v6A1.5 1.5 0 0 1 19.5 18H18" />
                                <rect x="6" y="14" width="12" height="7" rx="1.2" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" />
                            </svg>
                            Cetak QR aktif
                        </a>
                        <a href="{{ route('dashboard') }}"
                           class="pressable inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur hover:bg-white/20">
                            &larr; Kembali
                        </a>
                    </div>
                </div>
            </header>

            @if (session('success'))
                <div class="dashboard-enter flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white">&check;</span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="dashboard-enter flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-500 text-white">!</span>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Ringkasan --}}
            <div class="grid gap-5 sm:grid-cols-3">
                <div class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Kamar</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalRooms, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-500">Terdaftar di sistem</p>
                </div>
                <div class="reveal rounded-2xl border border-teal-200 bg-teal-50/60 p-6 shadow-sm" style="--reveal-delay: 70ms">
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-700">QR Aktif</p>
                    <p class="mt-2 text-3xl font-bold text-teal-900">{{ number_format($activeRooms, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-teal-700">Dapat memesan dari kamar</p>
                </div>
                <div class="reveal rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" style="--reveal-delay: 140ms">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">QR Nonaktif</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalRooms - $activeRooms, 0, ',', '.') }}</p>
                    <p class="mt-1 text-xs text-slate-500">Tidak bisa membuka menu</p>
                </div>
            </div>

            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.rooms.index') }}"
                  class="flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="min-w-[180px] flex-1">
                    <label for="q" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Cari kamar</label>
                    <input type="search" id="q" name="q" value="{{ $search }}" inputmode="numeric"
                           placeholder="Contoh: 101"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label for="status" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Status</label>
                    <select id="status" name="status"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua status</option>
                        <option value="active" @selected($status === 'active')>Aktif</option>
                        <option value="inactive" @selected($status === 'inactive')>Nonaktif</option>
                    </select>
                </div>
                <button type="submit"
                        class="rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition hover:from-teal-600 hover:to-emerald-700">
                    Terapkan
                </button>
                @if ($search !== '' || $status !== '')
                    <a href="{{ route('admin.rooms.index') }}"
                       class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Daftar QR --}}
            @if ($rooms->isEmpty())
                <section class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <p class="font-semibold text-slate-900">Tidak ada kamar yang cocok</p>
                    <p class="mt-1 text-sm text-slate-500">Ubah kata kunci atau filter status, lalu coba lagi.</p>
                </section>
            @else
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($rooms as $room)
                        @php
                            $guestUrl = url(route('guest.order.show', ['token' => $room->qr_token], false));
                        @endphp

                        <article class="reveal flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Kamar</p>
                                    <p class="text-xl font-bold tracking-tight text-slate-900">{{ $room->room_number }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold
                                    {{ $room->is_active ? 'border-teal-200 bg-teal-50 text-teal-700' : 'border-slate-200 bg-slate-100 text-slate-600' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $room->is_active ? 'bg-teal-500' : 'bg-slate-400' }}"></span>
                                    {{ $room->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>

                            <div class="flex flex-1 flex-col gap-4 p-5">
                                <div class="mx-auto w-full max-w-[200px] rounded-xl border border-slate-200 bg-white p-3">
                                    <img
                                        src="{{ route('admin.rooms.qr', $room) }}?v={{ substr($room->qr_token, 0, 10) }}"
                                        alt="QR menu untuk Kamar {{ $room->room_number }}"
                                        width="480"
                                        height="480"
                                        loading="lazy"
                                        decoding="async"
                                        class="h-auto w-full"
                                    >
                                </div>

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Tautan tamu</p>
                                    <p class="mt-1 break-all text-xs leading-relaxed text-slate-500" title="{{ $guestUrl }}">{{ $guestUrl }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ $guestUrl }}" target="_blank" rel="noopener"
                                       class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-center text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700">
                                        Buka menu
                                    </a>
                                    <a href="{{ route('admin.rooms.download', $room) }}"
                                       class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-center text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700">
                                        Unduh QR
                                    </a>
                                    <a href="{{ route('admin.rooms.print', ['room' => $room->id]) }}" target="_blank" rel="noopener"
                                       class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-center text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700">
                                        Cetak label
                                    </a>
                                    <button type="button"
                                            x-data="{ copied: false }"
                                            @click="if (navigator.clipboard) { navigator.clipboard.writeText(@js($guestUrl)).then(() => { copied = true; setTimeout(() => copied = false, 1600); }); }"
                                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700">
                                        <span x-show="!copied">Salin tautan</span>
                                        <span x-show="copied" x-cloak>Tersalin</span>
                                    </button>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 bg-slate-50/60 px-5 py-3">
                                <form method="POST" action="{{ route('admin.rooms.toggle', $room) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700">
                                        {{ $room->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.rooms.regenerate', $room) }}"
                                      onsubmit="return confirm('Buat ulang QR Kamar {{ $room->room_number }}? QR yang sudah dicetak tidak akan berlaku lagi.');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 transition hover:bg-amber-100">
                                        Buat ulang QR
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4">
                    {{ $rooms->links() }}
                </div>
            @endif

            <p class="rounded-2xl border border-sky-100 bg-sky-50/70 px-5 py-4 text-sm text-sky-800">
                <strong>Catatan:</strong> QR hanya bisa dipakai selama kamar berstatus aktif. Jika QR lama
                tersebar, gunakan <em>Buat ulang QR</em> agar tautan lama tidak berlaku.
            </p>
        </div>
    </main>
</x-layouts::app>
