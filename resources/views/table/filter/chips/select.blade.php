@use('Patrikjak\Utils\Table\ValueObjects\Filter\Criteria\SelectFilterCriteria')
@use('Patrikjak\Utils\Table\View\Filter\FilterOption')

@php(assert($option instanceof FilterOption))
@php(assert($option->criteria instanceof SelectFilterCriteria))

<span class="operator">:</span>
<span class="value">&nbsp;{{ $option->criteria->value }}</span>
