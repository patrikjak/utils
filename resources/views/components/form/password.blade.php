<x-pjutils::form.input type="password" {{ $attributes->merge() }} icon="eye" class="password hidden" />

@if(isset($confirm) && $confirm === true)
    <x-pjutils::form.input type="password"
                  name="{{ $name . '_confirmation' }}"
                  class="password hidden"
                  label="{{ $confirmLabel ?? $label }}"
                  autocomplete="{{ $autocomplete ?? '' }}"
                  placeholder="{{ $confirmPlaceholder ?? '' }}"
                  icon="eye"
    />
@endif