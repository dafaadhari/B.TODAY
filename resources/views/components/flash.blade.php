@props(['type' => 'success'])

<div role="{{ $type === 'error' ? 'alert' : 'status' }}" aria-live="polite"
    {{ $attributes->class([
        'rounded-xl border p-4 text-sm leading-relaxed',
        'border-success/30 bg-success-soft text-success' => $type === 'success',
        'border-danger/30 bg-danger-soft text-danger' => $type === 'error',
    ]) }}>
    {{ $slot }}
</div>
