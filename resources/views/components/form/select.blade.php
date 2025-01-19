<div class="{{ $wrapperClass }}">
    <label for="{{ $attributes->get('id', $attributes->get('name')) }}">{{ $label ?? '' }}</label>

    <select {{ $attributes->merge(['id' => $attributes->get('name'), 'required' => $required]) }}>
        @if(isset($options) && is_iterable($options))
            @foreach($options as $optionValue => $label)
                <x-pjutils::form.option
                    :value="$optionValue"
                    :label="$label"
                    :selected="is_array($value) ? in_array($optionValue, $value) : $value === $optionValue"
                />
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
</div>