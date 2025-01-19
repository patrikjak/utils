@props(['name', 'value'])

<x-pjutils::form.input {{ $attributes->merge(['type' => 'hidden']) }} />