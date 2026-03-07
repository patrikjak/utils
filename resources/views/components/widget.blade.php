<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($title)
        <div class="pj-widget-header">
            <div class="pj-widget-header-main">
                <p class="pj-widget-title">{{ $title }}</p>
                @if($subtitle)
                    <p class="pj-widget-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($action) && $action->isNotEmpty())
                <div class="pj-widget-header-action">
                    {{ $action }}
                </div>
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
