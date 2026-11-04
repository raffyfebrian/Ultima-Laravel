<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-7xl space-y-8">

            {{-- Page Header --}}
            <header class="mb-4">
                <div class="flex items-end justify-between gap-4 mb-3">
                    <div>
                        <p class="text-xs font-semibold tracking-wider text-teal-600 uppercase">
                            Administration
                        </p>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                            Manajemen Pengguna
                        </h1>
                        <p class="mt-1 text-sm text-slate-500">
                            Kelola akun staf dan role akses di sistem Ngoffe ID.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 hover:border-slate-300 transition">
                            ← Kembali
                        </a>
                    </div>
                </div>
            </header>

            {{-- Users Stats Card --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-slate-900">Daftar Pengguna</h2>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-semibold">
                        {{ $users->total() }} Akun
                    </span>
                </div>

                <div class="overflow-hidden rounded-xl border border-slate-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 font-medium text-slate-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-right font-medium text-slate-500 uppercase tracking-wider">Dibuat</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($users as $user)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-teal-100 flex items-center justify-center">
                                                <span class="text-teal-600 font-medium text-sm">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        @php $roleName = $user->roles->first()?->name ?? '-'; @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full
                                            {{ $roleName === 'superadmin' ? 'bg-teal-100 text-teal-700 border border-teal-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}
                                            text-xs font-semibold"
                                        >
                                            <span class="h-1.5 w-1.5 rounded-full
                                                {{ $roleName === 'superadmin' ? 'bg-teal-600' : 'bg-slate-400' }}
                                            "></span>
                                            {{ ucfirst($roleName) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <p class="text-sm font-medium text-slate-900">
                                            {{ $user->created_at?->format('d M Y') }}
                                        </p>
                                        <p class="text-xs text-slate-500">
                                            {{ $user->created_at?->format('H:i') }}
                                        </p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                                            </svg>
                                            <p class="font-medium text-slate-900">Belum ada pengguna</p>
                                            <p class="text-sm text-slate-500">Data pengguna akan tampil di sini setelah ditambahkan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="border-t border-slate-100 px-6 py-4 flex items-center justify-between">
                        <p class="text-sm text-slate-500">
                            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} data
                        </p>
                        {{ $users->links() }}
                    </div>
                @endif
            </section>

        </div>
    </main>
</x-layouts::app>
