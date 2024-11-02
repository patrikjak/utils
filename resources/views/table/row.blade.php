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

    @foreach($table->columns as $column)
        <x-dynamic-component :component="$getCellView($row[$column])"
                             :cell="$row[$column]"
                             :$column
        />
    @endforeach

    @if($hasActions())
        <x-pjutils.table::cells.actions.dots />
    @endif
</tr>