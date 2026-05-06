@use('Patrikjak\Utils\Table\Contracts\Renderable')

<div
    class="pj-table-wrapper"
    id="{{ $tableId }}"
    @if($table->htmlPartsUrl !== null) data-html-parts-url="{{ $table->htmlPartsUrl }}" data-restoring @endif
>
    <x-pjutils.table::toolbar :table="$table" />

    <div @class(['table-wrapper', 'sticky-header' => $table->stickyHeader])>
        <table
            class="{{ $tableClass }}"
            @if($table->expandable !== null) data-expandable="{{ $table->expandable }}" @endif
        >

            <x-pjutils.table::head :$table />
            <x-pjutils.table::body :$table />
        </table>
    </div>

    @if($table->hasDropdownActions())
        <x-pjutils.table::cells.actions.options :actions="$table->getDropdownActions()" />
    @endif

    @if($table->hasBulkActions())
        <x-pjutils.table::bulk-actions.wrapper :bulk-actions="$table->bulkActions" />
    @endif

    @if($table->hasPagination())
        <x-pjutils.table::pagination.paginator :pagination-settings="$table->paginationSettings" />
    @endif

    @if($table->htmlPartsUrl !== null)
        <div class="table-loader hidden" role="status" aria-label="Loading">
            <div class="loading-dots">
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
                <div class="loading-dot"></div>
            </div>
        </div>
    @endif
</div>
