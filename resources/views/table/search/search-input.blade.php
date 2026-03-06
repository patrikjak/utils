<div class="controller search-wrapper">
    <label>
        <input
            type="text"
            class="search-input"
            placeholder="{{ __('pjutils::table.search') }}"
            value="{{ $settings->searchQuery ?? '' }}"
        />
        @icon('search')
    </label>
</div>
