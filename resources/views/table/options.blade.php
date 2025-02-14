<div class="table-options">
    <div class="controllers">
        @if($table->isSortable())
            <x-pjutils.table::sort.sorter :criteria="[new \Patrikjak\Utils\Table\Dto\Sort\SortCriteria('users.location')]" :sortable-columns="$table->sortableColumns" />
        @endif
    </div>

    <div class="option-values">
        @if($table->isSortable())
            <div class="group sort-values">
                <x-pjutils.table::sort.values
                    :values="[new \Patrikjak\Utils\Table\Dto\Sort\SortCriteria('users.location')]"
                    :sortable-columns="$table->sortableColumns"
                />
            </div>
        @endif
    </div>
</div>