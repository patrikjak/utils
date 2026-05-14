<td class="{{ $cellClass }}">
    @if($icon !== null)
        <div class="icon-data">
            {!! $icon->toHtml() !!}
    @endif

    {!! $cellContent !!}

    @if($icon !== null)
        </div>
    @endif
</td>
