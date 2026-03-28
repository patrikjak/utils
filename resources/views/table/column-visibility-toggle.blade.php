<div class="controller column-visibility-controller" data-table-id="{{ $table->tableId }}">
    <div class="clickable">
        <x-heroicon-o-view-list class="icon" />
        <span>{{ __('pjutils::table.columns') }}</span>
    </div>
    <div class="options column-options">
        @foreach($table->columnVisibility->columns as $key => $label)
            <x-pjutils::form.checkbox
                :inline="true"
                :label="$label"
                :value="$key"
                :checked="in_array($key, $visibleColumns, true)"
                class="option column-option column-checkbox"
            />
        @endforeach
    </div>
</div>
