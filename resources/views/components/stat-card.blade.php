@props([
    'label' => '',
    'value' => 0,
    'color' => 'blue',
    'icon'  => '',
])

<div class="bc-stat-card">
    <div class="bc-stat-icon {{ $color }}">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    </div>
    <div>
        <p class="h4 mb-0" style="color: var(--bc-text);">{{ number_format($value) }}</p>
        <p class="small text-muted mb-0 mt-1">{{ $label }}</p>
    </div>
</div>
