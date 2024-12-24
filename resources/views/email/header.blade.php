<div class="header">
    <a href="{{ $appUrl }}">
        @if($useLogo && $logoPath !== null)
            <img src="{{ asset($logoPath) }}" alt="logo">
        @endif
        {{ $appName }}
    </a>
</div>