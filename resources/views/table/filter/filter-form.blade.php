@use('Patrikjak\Utils\Table\Enums\Filter\FilterType')

@include('pjutils::table.filter.filter-forms.' . $type->value)