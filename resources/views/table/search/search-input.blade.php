<div class="controller search-wrapper">
    <input
        type="text"
        class="search-input"
        placeholder="{{ __('pjutils::table.search') }}"
        value="{{ $settings->searchQuery ?? '' }}"
    />
    @icon('search')
</div>
