{{-- Simple barangay hall outline (vector mark, no external imagery) --}}
@props(['size' => 22])

<svg
    width="{{ (int) $size }}"
    height="{{ (int) $size }}"
    viewBox="0 0 24 24"
    fill="none"
    xmlns="http://www.w3.org/2000/svg"
    aria-hidden="true"
    focusable="false"
    {{ $attributes }}
>
    <path
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M4 12l8-6 8 6v9a1 1 0 01-1 1h-4v-6H9v6H5a1 1 0 01-1-1v-9z"
    />
</svg>
