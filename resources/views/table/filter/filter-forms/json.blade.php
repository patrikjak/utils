<x-pjutils::dropdown
    :label="__('pjutils::table.filter_type')"
    :items="$jsonFilterTypes"
    name="filter_type"
    data-filter-field="operator"
/>

<input type="hidden" name="json_path" value="{{ $jsonPath }}" data-filter-field="json-path">

<x-pjutils::form.input
    :label="__('pjutils::table.filter_value')"
    name="filter_value"
    :placeholder="__('pjutils::table.filter_value_placeholder')"
    autofocus
    data-filter-field="value"
/>
