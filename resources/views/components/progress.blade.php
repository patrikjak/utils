<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($showLabel)
        <div class="pj-progress-header">
            @if($label !== null)
                <span class="pj-progress-label">{{ $label }}</span>
            @endif
            <span class="pj-progress-value">{{ $clampedValue }}%</span>
        </div>
    @endif
    <div class="pj-progress-track">
        <div class="pj-progress-fill" style="width: {{ $clampedValue }}%"></div>
    </div>
</div>
