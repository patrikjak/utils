<form action="{{ $action }}"
      method="{{ $method }}"
      {{ $implodedDataAttributes }}
      {{ $attributes->merge() }}
>
    @csrf

    @if(in_array($originalMethod, ['PUT', 'PATCH', 'DELETE']))
        @method($originalMethod)
    @endif

    {{ $slot }}

    <x-button type="submit" :label="$actionLabel ?? __('actions.submit')" />
</form>