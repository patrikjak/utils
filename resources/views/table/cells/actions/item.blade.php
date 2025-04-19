@use('Patrikjak\Utils\Common\Enums\Icon')

<div class="{{ $actionItemClass }}">
    @if($icon !== null)
        <div class="icon {{ $icon }}-icon">
            @if($icon instanceof Icon)
                {!! $icon->getAsHtml() !!}
            @else
                @customIcon($icon)
            @endif
        </div>
    @endif

    <div class="label">
        <p>{{ $action->label }}</p>
    </div>
</div>