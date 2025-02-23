@use('Patrikjak\Utils\Common\Enums\Filter\FilterType')
@use('Patrikjak\Utils\Table\Dto\Filter\Definitions\NeedsData')
@use('Patrikjak\Utils\Table\Dto\Filter\Definitions\RangeData')

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

                @if($filterableColumn->filterDefinition instanceof RangeData)
                    @if($filterableColumn->filterDefinition->getMin() !== null)
                        data-from="{{ $filterableColumn->filterDefinition->getMin() }}"
                    @endif

                    @if($filterableColumn->filterDefinition->getMax() !== null)
                        data-to="{{ $filterableColumn->filterDefinition->getMax() }}"
                   @endif
                @endif
            >
                <span>{{ $filterableColumn->label }}</span>
            </div>
        @endforeach
    </div>
</div>