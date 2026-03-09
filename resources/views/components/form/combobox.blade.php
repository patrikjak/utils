<div class="{{ $wrapperClass }}">
    @if($label !== null)
        <label for="{{ $attributes->get('id', $attributes->get('name')) }}-search">{{ $label }}</label>
    @endif
    <div class="pj-combobox-control">
        <input
            type="hidden"
            name="{{ $attributes->get('name') }}"
            value="{{ $value ?? '' }}"
            class="pj-combobox-hidden"
        >
        <input
            type="text"
            id="{{ $attributes->get('id', $attributes->get('name')) }}-search"
            class="pj-combobox-search"
            value="{{ $displayValue }}"
            placeholder="{{ $placeholder ?? 'Search...' }}"
            autocomplete="off"
            aria-haspopup="listbox"
            aria-expanded="false"
            aria-autocomplete="list"
            readonly
        >
        <svg class="pj-combobox-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
        <ul class="pj-combobox-list" role="listbox" hidden>
            @if($placeholder !== null)
                <li class="pj-combobox-search-item">
                    <input
                        type="text"
                        class="pj-combobox-filter"
                        placeholder="{{ $placeholder }}"
                        autocomplete="off"
                    >
                </li>
            @else
                <li class="pj-combobox-search-item">
                    <input
                        type="text"
                        class="pj-combobox-filter"
                        placeholder="Search..."
                        autocomplete="off"
                    >
                </li>
            @endif
            @foreach($options as $optValue => $optLabel)
                <li
                    class="pj-combobox-option{{ (string) $optValue === (string) $value ? ' selected' : '' }}"
                    data-value="{{ $optValue }}"
                    role="option"
                    aria-selected="{{ (string) $optValue === (string) $value ? 'true' : 'false' }}"
                >
                    {{ $optLabel }}
                </li>
            @endforeach
            <li class="pj-combobox-empty" hidden>No results found</li>
        </ul>
    </div>
    @if($error !== null)
        <div class="pj-combobox-error">
            <p class="message">{{ $error }}</p>
        </div>
    @endif
</div>
