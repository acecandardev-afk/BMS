@props([
    'status' => 'default',
    'label'  => null,
])

@php
$map = [
    'pending'   => 'bg-warning bg-opacity-25 text-dark',
    'approved'  => 'bg-success bg-opacity-25 text-success',
    'rejected'  => 'bg-danger bg-opacity-25 text-danger',
    'released'  => 'bg-primary bg-opacity-25 text-primary',
    'open'      => 'bg-warning bg-opacity-25 text-dark',
    'ongoing'   => 'bg-warning bg-opacity-25 text-dark',
    'resolved'  => 'bg-success bg-opacity-25 text-success',
    'escalated' => 'bg-danger bg-opacity-25 text-danger',
    'closed'    => 'bg-secondary bg-opacity-25 text-secondary',
    'draft'     => 'bg-secondary bg-opacity-25 text-secondary',
    'active'    => 'bg-success bg-opacity-25 text-success',
    'repealed'  => 'bg-danger bg-opacity-25 text-danger',
    'admin'     => 'bg-primary bg-opacity-25 text-primary',
    'staff'     => 'bg-info bg-opacity-25 text-info',
    'signatory' => 'bg-primary bg-opacity-25 text-primary',
    'resident'  => 'bg-secondary bg-opacity-25 text-secondary',
    'default'   => 'bg-secondary bg-opacity-25 text-secondary',
];
$class = $map[$status] ?? $map['default'];
$text  = $label ?? ucfirst(str_replace('_', ' ', $status));
@endphp

<span class="badge bc-badge {{ $class }}">{{ $text }}</span>
