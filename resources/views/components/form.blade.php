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

    @isset($actionLabel)
        <x-pjutils::button>
            {{ $actionLabel }}
        </x-pjutils::button>
    @endisset
</form>