@props([
    'id'    => 'modal',
    'title' => 'Confirm Action',
    'size'  => 'md',
])

@php
$sizes = ['sm' => 'sm', 'md' => '', 'lg' => 'lg', 'xl' => 'xl'];
$sizeClass = $sizes[$size] ?? '';
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1">
    <div class="modal-dialog {{ $sizeClass ? 'modal-' . $sizeClass : '' }}">
        <div class="modal-content bc-modal border-0 shadow-lg">
            <div class="modal-header border-0 border-bottom">
                <h5 class="modal-title fw-semibold">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            @isset($footer)
            <div class="modal-footer border-0 border-top">
                {{ $footer }}
            </div>
            @endisset
        </div>
    </div>
</div>
