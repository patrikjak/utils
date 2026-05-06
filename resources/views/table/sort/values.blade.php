@use('Patrikjak\Utils\Common\Enums\Sort\SortOrder')

<div class="values">
    <div
        class="option sort-option"
        data-column="{{ $option->criteria->column }}"
        data-order="{{ $option->criteria->order }}"
    >
        <div class="icon sort-order">
            @if($option->criteria->order === SortOrder::ASC)
                @icon('heroicon-o-sort-ascending')
            @else
                @icon('heroicon-o-sort-descending')
            @endif
        </div>

        <span>{{ $option->label }}</span>

        <x-pjutils::close-button/>
    </div>
</div>