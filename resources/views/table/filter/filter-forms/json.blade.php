<x-pjutils::dropdown
    :label="__('pjutils::table.filter_type')"
    :items="$jsonFilterTypes"
    name="filter_type"
/>

<input type="hidden" name="json_path" value="{{ $jsonPath }}">

<x-pjutils::form.input
    :label="__('pjutils::table.filter_value')"
    name="filter_value"
    :placeholder="__('pjutils::table.filter_value_placeholder')"
    autofocus
/>
