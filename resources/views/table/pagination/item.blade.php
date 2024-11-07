@if($showDots)
    <span class="dots">...</span>
@endif

@if(!$skipPage)

    <a href="{{ $link->getUrl() }}" class="{{ $itemClass }}">
        @if(is_numeric($link->getLabel()))
            <span class="page-number">{{ $link->getLabel() }}</span>
        @else
            <div class="arrows">
                @if($isPrevArrow)
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                        <path d="M14 16L10 12L14 8" stroke="#010722" stroke-width="2" stroke-miterlimit="10"
                              stroke-linecap="square"/>
                    </svg>
                @elseif($isNextArrow)
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
                        <rect width="24" height="24" fill="white"/>
                        <path d="M10 8L14 12L10 16" stroke="#010722" stroke-width="2" stroke-miterlimit="10"
                              stroke-linecap="square"/>
                    </svg>
                @endif
            </div>
        @endif
    </a>

@endif
