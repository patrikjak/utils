@props(['confirm', 'confirmLabel', 'confirmPlaceholder'])

<div class="{{ $wrapperClass }}">
    @if(isset($label))
        <label for="{{ $inputAttributes['name'] }}">{{ $label }}</label>
    @endif

    @if(isset($icon) || isset($error))
        <div class="input">
            <input {{ $attributes->merge($inputAttributes) }}>

            <div class="icon">
                {!! $icon->getAsHtml() !!}
            </div>
        </div>
    @else
        <input {{ $attributes->merge($inputAttributes) }}>
    @endif

    @if(isset($error))
        <div class="error">
            <p class="message">{{ $error }}</p>
        </div>
    @endif
</div>