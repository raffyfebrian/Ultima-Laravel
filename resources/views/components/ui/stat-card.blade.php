@props([
    'label',
    'value' => '-',
    'description' => null,
])

<article class="rounded-2xl border border-black/5 bg-white p-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
        <p class="text-sm font-medium text-gray-500">
            {{ $label }}
        </p>

        <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-ngofee"></span>
    </div>

    <p class="mt-5 font-display text-3xl font-semibold tracking-tight text-charcoal">
        {{ $value }}
    </p>

    @if ($description)
        <p class="mt-2 text-xs leading-5 text-gray-400">
            {{ $description }}
        </p>
    @endif
</article>
