@use('Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\DateFilterCriteria')
@use('Patrikjak\Utils\Table\View\Filter\FilterOption')

@php(assert($option instanceof FilterOption))
@php(assert($option->criteria instanceof DateFilterCriteria))

<span class="operator">:</span>
@if($option->criteria->from !== null)
    <span class="from">
        &nbsp; {{ strtolower(__('pjutils::table.filter_from')) }} - {{ $option->criteria->from->format('d/m/Y') }}
    </span>
@endif

@if($option->criteria->to !== null)
    <span class="to">
        &nbsp; {{ strtolower(__('pjutils::table.filter_to')) }} - {{ $option->criteria->to->format('d/m/Y') }}
    </span>
@endif
