@props(['confirm', 'confirmLabel', 'confirmPlaceholder'])

<div class="{{ $wrapperClass }}">
    @if(isset($label))
        <label for="{{ $attributes->get('id', $attributes->get('name')) }}">{{ $label }}</label>
    @endif

    @if(isset($icon) || isset($error))
        <div class="input">
            <input {{ $attributes->merge(['type' => 'text', 'id' => $attributes->get('name')]) }}>

            <div class="icon {{ $icon }}-icon">
                {!! $icon->getAsHtml() !!}
            </div>
        </div>
    @else
        <input {{ $attributes->merge(['type' => 'text', 'id' => $attributes->get('name'), 'required' => $required]) }}>
    @endif

    @if(isset($error))
        <div class="error">
            <p class="message">{{ $error }}</p>
        </div>
    @endif
</div>