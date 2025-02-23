@use('Patrikjak\Utils\Common\Enums\Filter\FilterType')
@use('Patrikjak\Utils\Table\Dto\Filter\Definitions\NeedsData')

<div class="controller filter-options-wrapper">
    <div class="clickable">
        @icon('filter')
        <span>@lang('pjutils::table.filter')</span>
    </div>

    <div class="options">
        <span class="title">@lang('pjutils::table.filter_by')</span>

        @foreach($settings->filterableColumns as $filterableColumn)
            <div
                {{ $attributes->class(['option']) }}
                data-column="{{ $filterableColumn->column }}"
                data-type="{{ $filterableColumn->filterDefinition->getType()->value }}"
                @if($filterableColumn->filterDefinition instanceof NeedsData)
                    data-options-url="{{ $filterableColumn->filterDefinition->getDataUrl() }}"
                @endif
            >
                <span>{{ $filterableColumn->label }}</span>
            </div>
        @endforeach
    </div>
</div>