@use('Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\TextFilterCriteria')
@use('Patrikjak\Utils\Table\View\Filter\FilterOption')

@php(assert($option instanceof FilterOption))
@php(assert($option->criteria instanceof TextFilterCriteria))

<span class="operator">: {{ strtolower($option->criteria->filterType->toLabel()) }} -</span>
<span class="value">&nbsp;{{ $option->criteria->value }}</span>
