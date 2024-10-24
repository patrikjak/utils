<div class="{{ $wrapperClass }}">
    <label for="{{ $name }}">{{ $label }}</label>
    <textarea name="{{ $name }}" id="{{ $name }}">{{ $value ?? '' }}</textarea>
</div>