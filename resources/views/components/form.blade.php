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

    <x-pjutils::button>
        {{ $actionLabel ?? __('actions.submit') }}
    </x-pjutils::button>
</form>