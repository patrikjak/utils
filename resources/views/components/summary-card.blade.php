<div {{ $attributes->merge(['class' => 'pj-summary-card']) }}>
    <div class="pj-summary-card-header">
        @if($icon !== null)
            <span class="pj-summary-card-icon">{!! $icon->toHtml() !!}</span>
        @endif
        <p class="pj-summary-card-title">{{ $title }}</p>
        @if($status !== null && $statusLabel !== null)
            <x-pjutils::badge :type="$status">{{ $statusLabel }}</x-pjutils::badge>
        @endif
    </div>
    <div class="pj-summary-card-body">
        {{ $slot }}
    </div>
    @if(isset($actions) && $actions->isNotEmpty())
        <div class="pj-summary-card-footer">
            {{ $actions }}
        </div>
    @endif
</div>
