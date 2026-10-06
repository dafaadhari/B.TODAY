@props(['status'])

@php
    $classes = match($status) {
        'published', 'approved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30',
        'pending', 'pending_review' => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
        'draft' => 'bg-blue-500/10 text-blue-400 border-blue-500/30',
        'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/30',
        default => 'bg-gray-500/10 text-gray-400 border-gray-500/30',
    };

    $label = match($status) {
        'published' => 'Terbit',
        'approved' => 'Disetujui',
        'pending_review' => 'Menunggu Review',
        'pending' => 'Pending',
        'draft' => 'Draf',
        'rejected' => 'Ditolak',
        default => ucfirst($status),
    };
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $classes }}">
    {{ $label }}
</span>
