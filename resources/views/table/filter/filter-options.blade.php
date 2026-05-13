@use('Patrikjak\Utils\Table\Enums\Filter\FilterType')
@use('Patrikjak\Utils\Table\Contracts\Filter\NeedsData')
@use('Patrikjak\Utils\Table\Contracts\Filter\RangeData')
@use('Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\Json\JsonFilterDefinition')
@use('Patrikjak\Utils\Table\ValueObjects\Filter\Definitions\FilterableColumn')

<div class="controller filter-options-wrapper">
    <div class="clickable">
        @icon('heroicon-o-filter')
        <span>@lang('pjutils::table.filter')</span>
    </div>

    <div class="options">
        <span class="title">@lang('pjutils::table.filter_by')</span>

        @foreach($settings->filterableColumns as $filterableColumn)
            @php(assert($filterableColumn instanceof FilterableColumn))

            <div
                {{ $attributes->class(['option']) }}
                data-column="{{ $filterableColumn->column }}"
                data-type="{{ $filterableColumn->filterDefinition->getType() }}"
                @if($filterableColumn->filterDefinition instanceof NeedsData)
                    data-options-url="{{ $filterableColumn->filterDefinition->getDataUrl() }}"
                @endif

                @if($filterableColumn->filterDefinition instanceof JsonFilterDefinition)
                    data-json-path="{{ $filterableColumn->filterDefinition->jsonPath }}"
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