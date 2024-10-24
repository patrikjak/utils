<div class="{{ $wrapperClass }}">
    <label for="{{ $name }}">{{ $label ?? '' }}</label>

    <select {{ $attributes->merge(['name' => $name, 'id' => $name]) }}>
        @if(isset($options) && is_iterable($options))
            @foreach($options as $optionValue => $label)
                <x-pjutils::form.option :value="$optionValue"
                               :label="$label"
                               :selected="is_array($value) ? in_array($optionValue, $value) : $value === $optionValue"
                />
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
</div>