<tr id="{{ $rowId }}"
    @if(isset($rowClass)) class="{{ $rowClass }}" @endif
>
    @if($showCheckboxes())
        <td class="check">
            <x-pjutils::form.checkbox name="{{ $rowId }}"/>
        </td>
    @endif

    @if($showOrder())
        <td>{{ $loop->iteration }}</td>
    @endif


    @foreach($table->columns as $dataKey => $type)
        <x-dynamic-component :component="$getCell($type)"
                             :$table
                             :$row
                             :$dataKey
                             :$type
        />
    @endforeach

    @if($hasActions())
        <x-pjutils.table::cells.action :$table />
    @endif
</tr>