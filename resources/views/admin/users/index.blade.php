<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-6xl space-y-8">

            {{-- Header --}}
            <header class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-teal-600">Administration</p>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Manajemen Pengguna</h1>
                    <p class="mt-1 text-sm text-slate-500">Kelola akun staf dan role akses di sistem Ngoffe ID.</p>
                </div>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    &larr; Kembali
                </a>
            </header>

            @if (session('success'))
                <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white">&check;</span>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-medium text-rose-800">
                    <span class="flex h-6 w-6 items-center justify-center rounded-full bg-rose-500 text-white">!</span>
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800">
                    <p class="font-semibold">Periksa kembali data berikut:</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Add User --}}
            <details class="group rounded-2xl border border-slate-200 bg-white shadow-sm">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-5">
                    <div>
                        <h2 class="font-bold text-slate-900">Tambah Pengguna Baru</h2>
                        <p class="mt-1 text-sm text-slate-500">Buat akun staf dan tentukan role aksesnya.</p>
                    </div>
                    <span class="rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition group-open:hidden">
                        + Tambah
                    </span>
                </summary>

                <form method="POST" action="{{ route('admin.users.store') }}" class="grid gap-4 border-t border-slate-100 px-6 py-6 sm:grid-cols-2 lg:grid-cols-5">
                    @csrf
                    <div class="lg:col-span-2">
                        <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="lg:col-span-2">
                        <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label for="role" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Role</label>
                        <select id="role" name="role" required
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role') === $role->name)>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Password</label>
                        <input type="password" id="password" name="password" required minlength="6"
                               class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div class="flex items-end lg:col-span-3">
                        <button type="submit" class="rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition hover:-translate-y-0.5 hover:from-teal-600 hover:to-emerald-700 hover:shadow-xl">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </details>

            {{-- Users Table --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                    <div>
                        <h2 class="font-bold text-slate-900">Daftar Pengguna</h2>
                        <p class="mt-1 text-sm text-slate-500">Semua akun staf yang terdaftar.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-700">
                        {{ $users->total() }} Akun
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Nama</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Email</th>
                                <th class="px-6 py-4 font-medium uppercase tracking-wider text-slate-500">Role</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Dibuat</th>
                                <th class="px-6 py-4 text-right font-medium uppercase tracking-wider text-slate-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse ($users as $user)
                                @php
                                    $roleName = $user->roles->first()?->name ?? '-';
                                    $roleBadge = match ($roleName) {
                                        'superadmin' => 'bg-teal-100 text-teal-700 border-teal-200',
                                        'admin' => 'bg-sky-100 text-sky-700 border-sky-200',
                                        'cashier' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'kitchen' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-teal-100">
                                                <span class="text-sm font-semibold text-teal-700">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-500">
                                                    {{ $user->id === auth()->id() ? 'Akun Anda' : 'Staf' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $roleBadge }}">
                                            {{ ucfirst($roleName) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <p class="text-sm font-medium text-slate-900">{{ $user->created_at?->format('d M Y') }}</p>
                                        <p class="text-xs text-slate-500">{{ $user->created_at?->format('H:i') }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700">
                                                Edit
                                            </a>

                                            @if ($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100"
                                                            onclick="return confirm('Hapus pengguna {{ $user->name }}?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <p class="font-medium text-slate-900">Belum ada pengguna</p>
                                            <p class="text-sm text-slate-500">Tambahkan akun staf pertama lewat form di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
                        <p class="text-sm text-slate-500">
                            Menampilkan {{ $users->firstItem() }}&ndash;{{ $users->lastItem() }} dari {{ $users->total() }} data
                        </p>
                        {{ $users->links() }}
                    </div>
                @endif
            </section>

        </div>
    </main>
</x-layouts::app>
