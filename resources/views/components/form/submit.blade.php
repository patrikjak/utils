@props(['label'])

<input {{ $attributes->merge(['class' => 'be-btn']) }} type="submit" value="{{ $label }}">