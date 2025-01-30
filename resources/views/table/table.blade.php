<div
    class="pj-table-wrapper"
    id="{{ $tableId }}"
>
    @if($table->isSortable())
        <div class="table-options">
            <x-pjutils.table::sort.sorter :sortable-columns="$table->sortableColumns" />
        </div>
    @endif

    <table class="{{ $tableClass }}"
           @if($table->expandable !== null) data-expandable="{{ $table->expandable }}" @endif
    >

        <x-pjutils.table::head :$table />
        <x-pjutils.table::body :$table />

        @if($table->hasActions())
            <x-pjutils.table::cells.actions.options :actions="$table->actions" />
        @endif
    </table>

    @if($table->hasBulkActions())
        <x-pjutils.table::bulk-actions.wrapper :bulk-actions="$table->bulkActions" />
    @endif

    @if($table->hasPagination())
        <x-pjutils.table::pagination.paginator :pagination-settings="$table->paginationSettings" />
    @endif
</div>