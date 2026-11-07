<x-layouts::app>
    <main class="min-h-screen bg-[#f8fafc] px-6 py-8">
        <div class="mx-auto max-w-2xl space-y-8">

            <header>
                <a href="{{ route('admin.users.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-teal-600 transition hover:text-teal-700">
                    &larr; Kembali ke Manajemen Pengguna
                </a>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900">Edit Pengguna</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui data akun dan role akses.</p>
            </header>

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

            <form method="POST" action="{{ route('admin.users.update', $user) }}"
                  class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                @method('PATCH')

                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-teal-100">
                        <span class="text-xl font-semibold text-teal-700">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    </div>
                </div>

                <div>
                    <label for="name" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label for="email" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label for="role" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">Role</label>
                    <select id="role" name="role" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected(old('role', $user->getRoleNames()->first()) === $role->name)>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="password" class="mb-2 block text-xs font-semibold uppercase tracking-wider text-slate-500">
                        Password Baru <span class="font-normal normal-case text-slate-400">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" id="password" name="password" minlength="6"
                           class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm transition focus:border-teal-500 focus:ring-2 focus:ring-teal-500">
                </div>

                <div class="flex items-center gap-3 border-t border-slate-100 pt-5">
                    <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-900/20 transition hover:-translate-y-0.5 hover:from-teal-600 hover:to-emerald-700 hover:shadow-xl">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="rounded-xl border border-slate-200 px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </main>
</x-layouts::app>
