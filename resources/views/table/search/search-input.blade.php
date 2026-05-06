<div class="controller search-wrapper">
    <label>
        <input
            type="text"
            id="table-search"
            name="search"
            class="search-input"
            placeholder="{{ __('pjutils::table.search') }}"
            value="{{ $settings->searchQuery ?? '' }}"
        />
        @icon('heroicon-o-search')
    </label>
</div>
