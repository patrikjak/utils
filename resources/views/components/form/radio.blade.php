<div class="{{ $wrapperClass }}">
    <div class="group">
        <input {{ $attributes->merge([
                'type' => 'radio',
                'id' => $attributes->get('name'),
                'required' => $required,
                'value' => $value,
            ]) }}
        >
        <label for="{{ $attributes->get('id', $attributes->get('name')) }}">{{ $label }}</label>
    </div>
</div>
