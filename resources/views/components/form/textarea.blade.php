<div class="{{ $wrapperClass }}">
    <label for="{{ $name }}">{{ $label }}</label>
    <textarea {{ $attributes->merge(['name' => $name, 'id' => $name]) }}>{{ $value ?? '' }}</textarea>
</div>