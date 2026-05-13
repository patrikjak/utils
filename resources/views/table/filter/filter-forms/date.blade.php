<x-pjutils::form.date
    :label="__('pjutils::table.filter_from')"
    name="filter_value_from"
    :$min
    :$max
    data-filter-field="from"
/>

<x-pjutils::form.date
    :label="__('pjutils::table.filter_to')"
    name="filter_value_to"
    :$min
    :$max
    data-filter-field="to"
/>