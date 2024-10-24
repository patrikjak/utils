<td class="{{ $cellClass }}">
    @if($iconPath !== null)
        <div class="icon-data">
            @include('icons.' . $iconPath)
    @endif

    {{ $row[$dataKey] }}

    @if($iconPath !== null)
        </div>
   @endif

</td>