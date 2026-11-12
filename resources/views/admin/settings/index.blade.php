<x-layouts::app title="Pengaturan — Ngoffe ID">
    <div class="min-h-screen bg-[#F5F8F6] text-[#17221D]">
        <div class="flex min-h-screen">
            {{-- Sidebar --}}
            <x-dashboard.sidebar />

            {{-- Main Content --}}
            <main class="min-w-0 flex-1">
                <x-dashboard.page-header
                    title="Pengaturan"
                    breadcrumb="Ngoffe ID / Workspace / Pengaturan"
                />

                <section class="space-y-8 p-4 md:p-10">
                    {{-- Header --}}
                    <header class="dashboard-enter relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#102F27] via-[#174C3B] to-[#2F765B] p-6 text-white shadow-2xl shadow-[#102F27]/20 md:p-8">
                        <div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-[#D5F58A]/10 blur-3xl"></div>
                        <div class="absolute -bottom-24 left-1/4 h-56 w-56 rounded-full bg-[#BCEBD0]/10 blur-3xl"></div>
                        <div class="absolute inset-x-8 bottom-0 h-px bg-gradient-to-r from-transparent via-[#BCEBD0]/40 to-transparent"></div>

                        <div class="relative flex flex-wrap items-end justify-between gap-4">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#BCEBD0]">Workspace Settings</p>
                                <h1 class="mt-2 text-3xl font-bold tracking-tight">Pengaturan Workspace</h1>
                                <p class="mt-2 max-w-2xl text-sm leading-7 text-white/70">
                                    Kelola akses ke area operasional, termasuk pengelolaan kamar dan QR menu,
                                    serta lihat informasi dasar workspace yang sedang dipakai.
                                </p>
                            </div>

                            <a href="{{ route('dashboard') }}"
                               class="pressable inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white backdrop-blur hover:bg-white/20">
                                &larr; Kembali ke Dashboard
                            </a>
                        </div>
                    </header>

                    {{-- Kamar & QR --}}
                    <div class="reveal overflow-hidden rounded-3xl border border-[#E1EAE4] bg-white shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-5 p-6 md:p-8">
                            <div class="flex items-start gap-4">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-[#ECFDF5] text-[#0F766E]">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <rect x="3" y="3" width="7" height="7" rx="1.5" stroke-width="1.7" />
                                        <rect x="14" y="3" width="7" height="7" rx="1.5" stroke-width="1.7" />
                                        <rect x="3" y="14" width="7" height="7" rx="1.5" stroke-width="1.7" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M14 14h3v3h-3zM20 14v3M14 20h3M20 20h1" />
                                    </svg>
                                </span>

                                <div>
                                    <h2 class="text-lg font-bold text-[#17221D]">Kamar &amp; QR</h2>
                                    <p class="mt-1 max-w-xl text-sm leading-6 text-[#718078]">
                                        Setiap kamar memiliki QR unik untuk membuka menu dan mengirim pesanan.
                                        Unduh, cetak, atau buat ulang QR dari halaman pengelolaan kamar.
                                    </p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.rooms.index') }}"
                                   class="action-button inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#174C3B]">
                                    Kelola Kamar &amp; QR
                                </a>
                                <a href="{{ route('admin.rooms.print') }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="pressable inline-flex items-center gap-2 rounded-xl border border-[#E1EAE4] px-5 py-2.5 text-sm font-semibold text-[#17221D] hover:bg-[#F5F8F6]">
                                    Cetak QR aktif
                                </a>
                            </div>
                        </div>

                        <div class="border-t border-[#E1EAE4] bg-[#F9FBF9] px-6 py-4 text-xs leading-6 text-[#718078] md:px-8">
                            QR menu tetap dapat diakses dari dashboard melalui shortcut
                            <b class="font-semibold text-[#174C3B]">Kelola Kamar &amp; QR</b>.
                            Perubahan status kamar langsung memengaruhi QR yang dapat dipakai tamu.
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        {{-- Tampilan & Aksesibilitas --}}
                        <div class="reveal rounded-3xl border border-[#E1EAE4] bg-white p-6 shadow-sm md:p-8" style="--reveal-delay: 80ms">
                            <h2 class="text-lg font-bold text-[#17221D]">Tampilan &amp; Aksesibilitas</h2>
                            <p class="mt-1 text-sm text-[#718078]">Preferensi berikut mengikuti pengaturan perangkat Anda.</p>

                            <ul class="mt-5 space-y-4">
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#ECFDF5] text-[#0F766E]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v2m0 14v2M5.6 5.6l1.4 1.4m10 10 1.4 1.4M3 12h2m14 0h2M5.6 18.4 7 17m10-10 1.4-1.4" />
                                            <circle cx="12" cy="12" r="4" stroke-width="1.8" />
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-[#17221D]">Animasi dekoratif</p>
                                        <p class="mt-0.5 text-sm leading-6 text-[#718078]">
                                            Hero dan ilustrasi 3D berhenti bergerak otomatis saat
                                            <i>prefers-reduced-motion</i> aktif di perangkat.
                                        </p>
                                    </div>
                                </li>

                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#ECFDF5] text-[#0F766E]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.5A7.5 7.5 0 1 0 19.5 12H12V4.5Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.5 3.5A6.5 6.5 0 0 1 20.5 9.5H14.5v-6Z" />
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-[#17221D]">Tema Workspace</p>
                                        <p class="mt-0.5 text-sm leading-6 text-[#718078]">
                                            Workspace memakai latar terang agar nyaman dipakai lama, dengan hero
                                            hijau gelap sebagai penanda area utama.
                                        </p>
                                    </div>
                                </li>

                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#ECFDF5] text-[#0F766E]">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.5a7.5 7.5 0 0 0-7.5 7.5v4.5L3 18h18l-1.5-1.5V12A7.5 7.5 0 0 0 12 4.5Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.5 20a2.5 2.5 0 0 0 5 0" />
                                        </svg>
                                    </span>
                                    <div>
                                        <p class="text-sm font-semibold text-[#17221D]">Notifikasi operasional</p>
                                        <p class="mt-0.5 text-sm leading-6 text-[#718078]">
                                            Pesanan baru dan perubahan status ditampilkan sebagai toast singkat
                                            di sudut kanan atas dashboard.
                                        </p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        {{-- Informasi Sistem --}}
                        <div class="reveal rounded-3xl border border-[#E1EAE4] bg-white p-6 shadow-sm md:p-8" style="--reveal-delay: 140ms">
                            <h2 class="text-lg font-bold text-[#17221D]">Informasi Sistem</h2>
                            <p class="mt-1 text-sm text-[#718078]">Konfigurasi dasar yang dipakai workspace ini.</p>

                            <dl class="mt-5 divide-y divide-[#E1EAE4] text-sm">
                                <div class="flex items-center justify-between gap-4 py-3">
                                    <dt class="text-[#718078]">Nama aplikasi</dt>
                                    <dd class="text-right font-semibold text-[#17221D]">{{ config('app.name') }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 py-3">
                                    <dt class="text-[#718078]">Alamat aplikasi</dt>
                                    <dd class="text-right font-semibold text-[#17221D]">{{ config('app.url') }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 py-3">
                                    <dt class="text-[#718078]">Zona waktu</dt>
                                    <dd class="text-right font-semibold text-[#17221D]">{{ config('app.timezone') }}</dd>
                                </div>
                                <div class="flex items-center justify-between gap-4 py-3">
                                    <dt class="text-[#718078]">Bahasa</dt>
                                    <dd class="text-right font-semibold text-[#17221D]">{{ strtoupper(config('app.locale')) }}</dd>
                                </div>
                            </dl>

                            <p class="mt-4 text-xs leading-6 text-[#718078]">
                                Perubahan konfigurasi dilakukan melalui berkas <code class="rounded bg-[#F5F8F6] px-1.5 py-0.5 text-[#174C3B]">.env</code>
                                oleh administrator sistem.
                            </p>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</x-layouts::app>
