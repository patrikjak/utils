@use('Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\NumberFilterCriteria')
@use('Patrikjak\Utils\Table\View\Filter\FilterOption')

@php(assert($option instanceof FilterOption))
@php(assert($option->criteria instanceof NumberFilterCriteria))

<span class="operator">:</span>
@if($option->criteria->from !== null)
    <span class="from">&nbsp; {{ strtolower(__('pjutils::table.filter_min')) }} {{ $option->criteria->from }}</span>
@endif
@if($option->criteria->to !== null)
    <span class="to">&nbsp; {{ strtolower(__('pjutils::table.filter_max')) }} {{ $option->criteria->to }}</span>
@endif
