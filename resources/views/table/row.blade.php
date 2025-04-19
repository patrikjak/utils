<tr id="{{ $rowId }}"
    @if(isset($rowClass)) class="{{ $rowClass }}" @endif
    @isset($hiddenActions) data-hidden-actions="{{ $hiddenActions }}" @endisset
>
    @if($showCheckboxes())
        <td class="check">
            <x-pjutils::form.checkbox />
        </td>
    @endif

    @if($showOrder())
        <td>{{ ((($table->paginationSettings?->page ?? 1) - 1) * $table->paginationSettings?->pageSize ?? 1) + $loop->iteration }}</td>
    @endif

    @foreach($table->columns as $column)
        <x-dynamic-component :component="$getCellView($row[$column])"
                             :cell="$row[$column]"
                             :$column
        />
    @endforeach

    @if($hasActions())
        @if($allActionsAreHidden)
            <td></td>
        @else
            <x-pjutils.table::cells.actions.dots />
        @endif
    @endif
</tr>