@props(['label'])

<input {{ $attributes->merge(['class' => 'pj-btn']) }} type="submit" value="{{ $label }}">