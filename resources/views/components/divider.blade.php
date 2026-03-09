@if($label)
    <div {{ $attributes->merge(['class' => 'pj-divider pj-divider-labeled']) }}>
        <span class="pj-divider-label">{{ $label }}</span>
    </div>
@else
    <hr {{ $attributes->merge(['class' => 'pj-divider']) }}>
@endif
