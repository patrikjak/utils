<div class="{{ $wrapperClass }}">
    @if($label !== null)
        <label>{{ $label }}</label>
    @endif
    <div class="pj-tags-control" tabindex="0">
        @foreach($value as $tag)
            <span class="pj-tag-chip">
                <span class="pj-tag-text">{{ $tag }}</span>
                <button type="button" class="pj-tag-remove" aria-label="Remove {{ $tag }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <input type="hidden" name="{{ $attributes->get('name') }}[]" value="{{ $tag }}">
            </span>
        @endforeach
        <input
            type="text"
            class="pj-tags-input"
            placeholder="{{ count($value) === 0 ? ($placeholder ?? 'Add tag...') : '' }}"
            data-name="{{ $attributes->get('name') }}"
            autocomplete="off"
            aria-label="{{ $label ?? 'Tags' }}"
        >
    </div>
    @if($error !== null)
        <div class="pj-tags-error">
            <p class="message">{{ $error }}</p>
        </div>
    @endif
</div>
