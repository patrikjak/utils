<div class="table-actions">
    @foreach($actions as $action)
        <x-pjutils.table::action-item :$action />
    @endforeach
</div>