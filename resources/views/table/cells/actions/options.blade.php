<div class="table-actions">
    @foreach($actions as $action)
        <x-pjutils.table::cells.actions.item :$action />
    @endforeach
</div>