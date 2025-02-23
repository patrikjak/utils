@use('Patrikjak\Utils\Table\Dto\Filter\Criteria\DateFilterCriteria;use Patrikjak\Utils\Table\Dto\Filter\Criteria\NumberFilterCriteria;use Patrikjak\Utils\Table\Dto\Filter\Criteria\SelectFilterCriteria;use Patrikjak\Utils\Table\Dto\Filter\Criteria\TextFilterCriteria;use Patrikjak\Utils\Table\Enums\Filter\FilterType')
@use('Patrikjak\Utils\Table\Dto\Filter\Criteria\TextFilterCriteria')
@use('Patrikjak\Utils\Table\Dto\Filter\Criteria\SelectFilterCriteria')
@use('Patrikjak\Utils\Table\Dto\Filter\Criteria\DateFilterCriteria')
@use('Patrikjak\Utils\Table\Dto\Filter\Criteria\NumberFilterCriteria')

@lang('pjutils::table.filtered_by')

<div class="values">
    @foreach($options as $option)
        <div
            class="option filter-option"
            data-column="{{ $option->criteria->column }}"
            data-type="{{ $option->criteria->getType()->value }}"
            @if($option->criteria instanceof TextFilterCriteria) data-operator="{{ $option->criteria->filterType->value }}" @endif
            @if($option->criteria instanceof TextFilterCriteria || $option->criteria instanceof SelectFilterCriteria)
                data-value="{{ $option->criteria->value }}"
            @endif
            @if($option->criteria instanceof DateFilterCriteria || $option->criteria instanceof NumberFilterCriteria)
                @if($option->criteria->from !== null) data-from="{{ $option->criteria->from }}" @endif
            @if($option->criteria->to !== null) data-to="{{ $option->criteria->to }}" @endif
            @endif
        >
            <span class="label">{{ $option->label }}</span>

            @if(in_array($option->criteria->getType(), [FilterType::SELECT, FilterType::NUMBER, FilterType::DATE]))
                <span class="operator">:</span>
            @endif

            @if($option->criteria instanceof TextFilterCriteria)
                <span class="operator">: {{ strtolower($option->criteria->filterType->toLabel()) }} -</span>
            @endif

            @if($option->criteria instanceof SelectFilterCriteria || $option->criteria instanceof TextFilterCriteria)
                <span class="value">&nbsp;{{ $option->criteria->value }}</span>
            @endif

            @if($option->criteria instanceof DateFilterCriteria)
                @if($option->criteria->from !== null)
                    <span
                        class="from">&nbsp; {{ strtolower(__('pjutils::table.filter_from')) }} - {{ $option->criteria->getFormattedFrom() }}</span>
                @endif

                @if($option->criteria->to !== null)
                    <span
                        class="to">&nbsp; {{ strtolower(__('pjutils::table.filter_to')) }} - {{ $option->criteria->getFormattedTo() }}</span>
                @endif
            @endif

            @if($option->criteria instanceof NumberFilterCriteria)
                @if($option->criteria->from !== null)
                    <span
                        class="from">&nbsp; {{ strtolower(__('pjutils::table.filter_min')) }} {{ $option->criteria->from }}</span>
                @endif

                @if($option->criteria->to !== null)
                    <span class="to">&nbsp; {{ strtolower(__('pjutils::table.filter_max')) }} {{ $option->criteria->to }}</span>
                @endif
            @endif

            <x-pjutils::close-button/>
        </div>
    @endforeach
</div>