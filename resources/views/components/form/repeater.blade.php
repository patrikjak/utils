@php
    $firstRow = str_replace('{index}', '0', $slot->toHtml());
@endphp

<div
    class="pj-repeater"
    data-min="{{ $min }}"
    @if($max !== null) data-max="{{ $max }}" @endif
>
    <div class="pj-repeater-rows">
        <div class="pj-repeater-row" data-index="0">
            {!! $firstRow !!}
            <button type="button" class="pj-repeater-remove" @if($min >= 1) disabled @endif>
                {{ $removeLabel }}
            </button>
        </div>
    </div>

    <template class="pj-repeater-template">
        <div class="pj-repeater-row" data-index="{index}">
            {!! $slot !!}
            <button type="button" class="pj-repeater-remove">{{ $removeLabel }}</button>
        </div>
    </template>

    <button
        type="button"
        class="pj-repeater-add pj-btn"
        @if($max !== null && $max <= 1) disabled @endif
    >
        + {{ $addLabel }}
    </button>
</div>
