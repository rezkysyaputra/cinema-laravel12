@props(['status'])

@if ($status)
<div {{ $attributes->merge(['class' => 'font-medium text-sm text-orange-500']) }}>
    {{ $status }}
</div>
@endif
