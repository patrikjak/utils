@if(isset($href))
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
        @if($loading)
            <div class="loader">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
        @endif
    </button>
@endif