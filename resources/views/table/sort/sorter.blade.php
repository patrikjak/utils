<div class="controller sorter-wrapper">
    <div class="clickable">
        @icon('sort')
        <span>@lang('pjutils::table.sort')</span>
    </div>

    <div class="options">
        <span class="title">@lang('pjutils::table.sort_by')</span>

        @foreach($settings->sortableColumns as $sortableColumn)
            <div
                {{ $attributes->class(['option', 'selected' => $sortableColumn->column === $selectedColumn]) }}
                data-column="{{ $sortableColumn->column }}"
            >
                <span>{{ $sortableColumn->label }}</span>
            </div>
        @endforeach
    </div>
</div>