<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($title)
        <div class="pj-widget-header">
            <p class="pj-widget-title">{{ $title }}</p>
            @if($subtitle)
                <p class="pj-widget-subtitle">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    <div class="pj-widget-body">
        {{ $slot }}
    </div>

    @if(isset($footer) && $footer->isNotEmpty())
        <div class="pj-widget-footer">
            {{ $footer }}
        </div>
    @endif
</div>
