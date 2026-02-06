<x-pjutils::dropdown
    :label="__('pjutils::table.filter_type')"
    :items="$jsonFilterTypes"
    name="filter_type"
/>

<x-pjutils::form.input
    :label="__('pjutils::table.json_path')"
    name="json_path"
    :placeholder="__('pjutils::table.json_path_placeholder')"
/>

<x-pjutils::form.input
    :label="__('pjutils::table.filter_value')"
    name="filter_value"
    :placeholder="__('pjutils::table.filter_value_placeholder')"
    autofocus
/>
