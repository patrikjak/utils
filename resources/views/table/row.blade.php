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


    @foreach($table->columnTypes as $dataKey => $columnType)
        <x-dynamic-component :component="$getCell($columnType)"
                             :$table
                             :$row
                             :$dataKey
                             :$columnType
        />
    @endforeach

    @if($hasActions())
        <x-pjutils.table::cells.action :$table />
    @endif
</tr>