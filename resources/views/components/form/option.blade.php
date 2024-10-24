@props(['value', 'label', 'selected' => false])

<option {{ $attributes->merge(['value' => $value]) }} @selected($selected)>{{ $label }}</option>