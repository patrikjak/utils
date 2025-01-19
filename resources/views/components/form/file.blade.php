<div class="{{ $wrapperClass }}">
    @if(isset($label))
        <label for="{{ $attributes->get('id', $attributes->get('name')) }}">{{ $label }}</label>
    @endif

    <input type="file" {{ $attributes->merge(['id' => $attributes->get('name'), 'required' => $required]) }}>

    @if(isset($error))
        <div class="error">
            <p class="message">{{ $error }}</p>
        </div>
    @endif
</div>