<div
    class="pj-table-wrapper"
    id="{{ $tableId }}"
>
    <table class="{{ $tableClass }}"
           @if($table->expandable !== null) data-expandable="{{ $table->expandable }}" @endif
    >

        <x-pjutils::table.head :$table />
        <x-pjutils::table.body :$table />

        @if($table->hasActions())
            <x-pjutils::table.actions :actions="$table->actions" />
        @endif
    </table>

    @if($table->hasPagination())
        <x-pjutils::table.pagination :pagination-settings="$table->paginationSettings" />
    @endif
</div>