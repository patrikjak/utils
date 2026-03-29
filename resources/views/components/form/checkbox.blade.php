@if($inline)
    <label {{ $attributes->merge(['class' => $wrapperClass]) }}>
        <input {{ $attributes->only(['value', 'checked', 'disabled'])->merge(['type' => 'checkbox']) }}>
        <span>{{ $label ?? $slot }}</span>
    </label>
@else
    <div class="{{ $wrapperClass }}">
        <div class="group">
            <input {{ $attributes->merge(['type' => 'checkbox', 'id' => $attributes->get('name'), 'required' => $required]) }}>

            <label for="{{ $attributes->get('id', $attributes->get('name')) }}">{{ $label ?? '' }}</label>
        </div>
    </div>
@endif
