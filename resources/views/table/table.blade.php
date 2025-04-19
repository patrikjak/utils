@use('Patrikjak\Utils\Table\Services\Renderable')

<div
    class="pj-table-wrapper"
    id="{{ $tableId }}"
    @if($table->htmlPartsUrl !== null) data-html-parts-url="{{ $table->htmlPartsUrl }}" @endif
>
    @if($showOptions)
        <x-pjutils.table::options :table="$table" />
    @endif

    <div class="table-wrapper">
        <table
            class="{{ $tableClass }}"
            @if($table->expandable !== null) data-expandable="{{ $table->expandable }}" @endif
        >

            <x-pjutils.table::head :$table />
            <x-pjutils.table::body :$table />

            @if($table->hasActions())
                <x-pjutils.table::cells.actions.options :actions="$table->actions" />
            @endif
        </table>
    </div>

    @if($table->hasBulkActions())
        <x-pjutils.table::bulk-actions.wrapper :bulk-actions="$table->bulkActions" />
    @endif

    @if($table->hasPagination())
        <x-pjutils.table::pagination.paginator :pagination-settings="$table->paginationSettings" />
    @endif
</div>