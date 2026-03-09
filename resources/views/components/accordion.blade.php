<div class="pj-accordion{{ $open ? ' open' : '' }}" {{ $attributes }}>
    <button
        type="button"
        class="pj-accordion-header"
        aria-expanded="{{ $open ? 'true' : 'false' }}"
    >
        <span class="pj-accordion-title">{{ $title }}</span>
        <svg class="pj-accordion-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </button>
    <div class="pj-accordion-body">
        <div class="pj-accordion-content">
            {{ $slot }}
        </div>
    </div>
</div>
