@use('Patrikjak\Utils\Table\Dto\Sort\Settings')

<div class="table-options">
    <div class="controllers">
        @if($table->isSortable())
            <x-pjutils.table::sort.sorter :settings="$table->sortSettings"/>
        @endif

        @if($table->isFilterable())
            <x-pjutils.table::filter.filter-options :settings="$table->filterSettings"/>
        @endif
    </div>

    @php
        $sorted = $table->isSortable() && $table->sortSettings->criteria !== null;
        $filtered = $table->isFilterable() && $table->filterSettings->criteria !== null;
    @endphp

    @if($sorted || $filtered)
        <div class="option-values">
            @if($sorted)
                <div class="group sort-values">
                    <x-pjutils.table::sort.values :settings="$table->sortSettings" />
                </div>
            @endif

            @if($filtered)
                <div class="group filter-values">
                    <x-pjutils.table::filter.values :settings="$table->filterSettings" />
                </div>
            @endif
        </div>
    @endif
</div>