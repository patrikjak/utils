<div {{ $attributes->merge(['class' => 'pj-stat-card']) }}>
    <p class="pj-stat-card-label">{{ $label }}</p>
    <p class="pj-stat-card-value">{{ $value }}</p>
    @if($trend !== null)
        <span class="pj-stat-card-trend {{ $trendDirection }}">
            @if($trendDirection === 'up')&#x2191;@elseif($trendDirection === 'down')&#x2193;@else&mdash;@endif
            {{ $trend }}
        </span>
    @endif
</div>
