<div class="{{ $actionItemClass }}">
    @if($icon !== null)
        <div class="icon {{ $icon }}-icon">
            {!! $icon->getAsHtml() !!}
        </div>
    @endif

    <div class="label">
        <p>{{ $action->label }}</p>
    </div>
</div>