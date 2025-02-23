<x-pjutils::form.input
    type="number"
    :label="__('pjutils::table.filter_min')"
    name="filter_value_from"
    :$min
    :$max
/>

<x-pjutils::form.input
    type="number"
    :label="__('pjutils::table.filter_max')"
    name="filter_value_to"
    :$min
    :$max
/>