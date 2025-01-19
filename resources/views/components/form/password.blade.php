@use('Patrikjak\Utils\Common\Enums\Icon')

<x-pjutils::form.input {{ $attributes->merge(['type' => 'password']) }} :icon="Icon::EYE" class="password hidden" />

@if(isset($confirm) && $confirm === true)
    <x-pjutils::form.input
        type="password"
        name="{{ $name . '_confirmation' }}"
        class="password hidden"
        label="{{ $confirmLabel ?? $label }}"
        autocomplete="{{ $autocomplete ?? '' }}"
        placeholder="{{ $confirmPlaceholder ?? '' }}"
        :icon="Icon::EYE"
    />
@endif