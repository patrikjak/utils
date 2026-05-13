@use('Patrikjak\Utils\Table\View\Filter\FilterOption')

@lang('pjutils::table.filtered_by')

<div class="values">
    @foreach($options as $option)
        @php(assert($option instanceof FilterOption))

        <div
            class="option filter-option"
            data-column="{{ $option->criteria->column }}"
            data-type="{{ $option->criteria->getType() }}"
            @foreach($option->criteria->getFilterData() as $key => $value)
                @if($value !== null) data-{{ $key }}="{{ $value }}" @endif
            @endforeach
        >
            <span class="label">{{ $option->label }}</span>

            @include($option->chipView)

            <x-pjutils::close-button/>
        </div>
    @endforeach
</div>
