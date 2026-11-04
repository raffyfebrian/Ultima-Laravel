
@props([
    'type' => 'button',
])

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'inline-flex items-center justify-center gap-2 rounded-xl bg-teal px-4 py-2.5 text-sm font-semibold text-white transition duration-200 hover:bg-teal/90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-teal disabled:cursor-not-allowed disabled:opacity-50',
    ]) }}
>
    {{ $slot }}
</button>
