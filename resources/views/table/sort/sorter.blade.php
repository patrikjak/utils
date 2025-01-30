<div class="sorter-wrapper">
    <div class="clickable">
        @icon('sort')
        <span>@lang('pjutils::table.sort')</span>
    </div>

    <div class="options">
        @foreach($sortableColumns as $column)
            <div class="option">
                <span data-column="{{ $column->column }}">{{ $column->label }}</span>
            </div>
        @endforeach
    </div>
</div>