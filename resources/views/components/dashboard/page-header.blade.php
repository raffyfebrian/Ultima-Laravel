
@props([
    'title' => 'Dashboard',
    'breadcrumb' => 'Ngoffe ID / Workspace',
])

<header class="flex items-center justify-between gap-4 border-b border-black/10 bg-white/80 px-6 py-5 md:px-10">
    <div class="min-w-0">
        <p class="text-sm text-gray-500">
            {{ $breadcrumb }}
        </p>

        <h1 class="mt-1 font-display text-xl font-semibold text-charcoal md:text-2xl">
            {{ $title }}
        </h1>
    </div>

    <span class="shrink-0 rounded-full bg-teal/10 px-3 py-2 text-xs font-semibold capitalize text-teal">
        {{ Auth::user()?->getRoleNames()->first() ?? 'No Role' }}
    </span>
</header>
