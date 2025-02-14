<div class="controller sorter-wrapper">
    <div class="clickable">
        @icon('sort')
        <span>@lang('pjutils::table.sort')</span>
    </div>

    <div class="options">
        <span class="title">@lang('pjutils::table.sort_by')</span>

        @foreach($sortableColumns as $column)
            <div
                {{ $attributes->class(['option', 'selected' => in_array($column->column, $selectedColumns, true)]) }}
                data-column="{{ $column->column }}"
            >
                <span>{{ $column->label }}</span>
            </div>
        @endforeach
    </div>
</div>