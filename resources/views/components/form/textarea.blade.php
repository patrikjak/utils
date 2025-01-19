<div class="{{ $wrapperClass }}">
    <label for="{{ $attributes->get('id', $attributes->get('name')) }}">{{ $label }}</label>
    <textarea {{ $attributes->merge(['id' => $attributes->get('name'), 'required' => $required]) }}>{{ $value ?? '' }}</textarea>
</div>