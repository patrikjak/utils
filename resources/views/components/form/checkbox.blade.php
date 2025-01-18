<div class="{{ $wrapperClass }}">
    <div class="group">
        <input {{ $attributes->merge(['type' => 'checkbox', 'id' => $attributes->get('name'), 'required' => $required]) }}>

        <label for="{{ $attributes->get('id', $attributes->get('name')) }}">{{ $label ?? '' }}</label>
    </div>
</div>