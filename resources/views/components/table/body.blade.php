<tbody>
@if($emptyBody())
    <x-pjutils::table.empty-row :$table />
@else
    @foreach($table->data as $row)
        <x-pjutils::table.row :$table :$row :$loop />
    @endforeach
@endif

{{-- TODO: expandable rows --}}
</tbody>