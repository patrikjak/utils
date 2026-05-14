@use('Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\JsonFilterCriteria')
@use('Patrikjak\Utils\Table\View\Filter\FilterOption')

@php(assert($option instanceof FilterOption))
@php(assert($option->criteria instanceof JsonFilterCriteria))

<span class="operator">: {{ strtolower($option->criteria->filterType->toLabel()) }} -</span>
<span class="value">&nbsp;{{ $option->criteria->value }}</span>
