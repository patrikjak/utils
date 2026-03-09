<div class="{{ $wrapperClass }}">
    @if($label !== null)
        <label for="{{ $attributes->get('id', $attributes->get('name')) }}">{{ $label }}</label>
    @endif
    <div class="pj-number-control">
        <button type="button" class="pj-number-btn pj-number-minus" aria-label="Decrease">−</button>
        <input
            type="number"
            {{ $attributes->merge([
                'value' => $value,
                'step' => $step,
                'id' => $attributes->get('name'),
            ]) }}
            @if($min !== null) min="{{ $min }}" @endif
            @if($max !== null) max="{{ $max }}" @endif
        >
        <button type="button" class="pj-number-btn pj-number-plus" aria-label="Increase">+</button>
    </div>
    @if($error !== null)
        <div class="pj-number-error">
            <p class="message">{{ $error }}</p>
        </div>
    @endif
</div>
