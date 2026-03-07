<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="pj-alert-body">
        @if($title)
            <p class="pj-alert-title">{{ $title }}</p>
        @endif
        <p class="pj-alert-message">{{ $slot }}</p>
    </div>
    @if($dismissible)
        <button type="button" class="pj-alert-dismiss" aria-label="Dismiss">&times;</button>
    @endif
</div>
