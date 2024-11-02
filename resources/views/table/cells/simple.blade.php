<td class="{{ $cellClass }}">
    @if($icon !== null)
        <div class="icon-data">
            {!! $icon->getAsHtml() !!}
    @endif

    {{ $cell->value }}

    @if($icon !== null)
        </div>
    @endif
</td>