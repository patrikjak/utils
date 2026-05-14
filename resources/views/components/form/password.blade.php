@use('Patrikjak\Utils\Common\Icon')

<x-pjutils::form.input {{ $attributes->merge(['type' => 'password']) }} :icon="Icon::heroicon('heroicon-o-eye')" class="password hidden" />

@if(isset($confirm) && $confirm === true)
    <x-pjutils::form.input
        type="password"
        name="{{ $name . '_confirmation' }}"
        class="password hidden"
        label="{{ $confirmLabel ?? $label }}"
        autocomplete="{{ $autocomplete ?? '' }}"
        placeholder="{{ $confirmPlaceholder ?? '' }}"
        :icon="Icon::heroicon('heroicon-o-eye')"
    />
@endif