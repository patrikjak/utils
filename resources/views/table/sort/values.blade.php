@use('Patrikjak\Utils\Table\Dto\Sort\SortOrder')

@lang('pjutils::table.sorted_by')

<div class="values">
    @foreach($options as $option)
        <div
            class="option sort-option"
            data-column="{{ $option->criteria->column }}"
            data-order="{{ $option->criteria->order }}"
        >
            <div class="icon">
                @if($option->criteria->order === SortOrder::ASC)
                    @icon('sort_asc')
                @else
                    @icon('sort_desc')
                @endif
            </div>

            <span>{{ $option->label }}</span>

            <x-pjutils::close-button/>
        </div>
    @endforeach
</div>