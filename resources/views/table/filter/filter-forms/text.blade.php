<x-pjutils::dropdown
    :label="__('pjutils::table.filter_type')"
    :items="$textFilterTypes"
    name="filter_type"
    data-filter-field="operator"
/>

<x-pjutils::form.input
    :label="__('pjutils::table.filter_value')"
    name="filter_value"
    :placeholder="__('pjutils::table.filter_value_placeholder')"
    data-filter-field="value"
/>
