<div class="{{ $wrapperClass }}">
    @if(isset($label))
        <label for="{{ $attributes['name'] }}">{{ $label }}</label>
    @endif

    <input type="file" {{ $attributes->merge(['name' => $name, 'id' => $name]) }}>

    @if(isset($error))
        <div class="error">
            <p class="message">{{ $error }}</p>
        </div>
    @endif
</div>