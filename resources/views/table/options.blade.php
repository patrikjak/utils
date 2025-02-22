@use('Patrikjak\Utils\Table\Dto\Sort\Settings')

<div class="table-options">
    <div class="controllers">
        @if($table->isSortable())
            <x-pjutils.table::sort.sorter :settings="$table->sortSettings" />
        @endif

        @if($table->isFilterable())
            <x-pjutils.table::filter.filter-options :settings="$table->filterSettings" />
        @endif
    </div>

    <div class="option-values">
        @if($table->isSortable() && $table->sortSettings->criteria !== null)
            <div class="group sort-values">
                <x-pjutils.table::sort.values :settings="$table->sortSettings" />
            </div>
        @endif
    </div>
</div>