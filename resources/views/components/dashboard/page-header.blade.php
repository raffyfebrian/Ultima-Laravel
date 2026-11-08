@props([
    'title' => 'Dashboard',
    'breadcrumb' => 'Ngoffe ID / Workspace',
])

<header class="dashboard-enter sticky top-0 z-30 flex items-center justify-between gap-4 border-b border-black/10 bg-white/90 px-4 py-4 backdrop-blur md:px-10 md:py-5">
    <div class="flex min-w-0 items-center gap-3">
        <button
            type="button"
            x-data
            @click="$dispatch('toggle-sidebar')"
            class="pressable flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-black/10 text-charcoal hover:bg-ngofee-bg focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ngofee md:hidden"
            aria-label="Buka menu navigasi"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="min-w-0">
            <p class="text-sm text-gray-500">
                {{ $breadcrumb }}
            </p>

            <h1 class="mt-1 truncate font-display text-xl font-semibold text-charcoal md:text-2xl">
                {{ $title }}
            </h1>
        </div>
    </div>

    <span class="shrink-0 rounded-full border border-ngofee-border bg-ngofee-bg px-3 py-2 text-xs font-semibold capitalize text-ngofee">
        {{ Auth::user()?->getRoleNames()->first() ?? 'No Role' }}
    </span>
</header>
