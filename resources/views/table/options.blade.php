@use('Patrikjak\Utils\Table\Dto\Sort\Settings')

<div class="table-options">
    <div class="controllers">
        @if($table->isFilterable())
            <x-pjutils.table::filter.filter-options :settings="$table->filterSettings"/>
        @endif

        @if($table->isSearchable())
            <x-pjutils.table::search.search-input :settings="$table->searchSettings"/>
        @endif

        @if($table->hasColumnVisibility())
            <x-pjutils.table::column-visibility-toggle :table="$table"/>
        @endif
    </div>

    @php
        $filtered = $table->isFilterable() && $table->filterSettings->criteria !== null;
    @endphp

    @if($filtered)
        <div class="option-values">
            <div class="group filter-values">
                <x-pjutils.table::filter.values :settings="$table->filterSettings" />
            </div>
        </div>
    @endif
</div>