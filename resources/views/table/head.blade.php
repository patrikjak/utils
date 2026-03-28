<thead>
    <tr>
        @if($showCheckboxes())
            <th class="check">
                <x-pjutils::form.checkbox />
            </th>
        @endif

        @if($showOrder())
            <th>#</th>
        @endif

        @foreach($headerData as $key => $value)
            @php
                $isSortable = in_array($key, $sortableColumnKeys, true);
                $isActive = $isSortable && $activeSortColumn === $key;
                $thClass = $isSortable ? 'sortable' . ($isActive ? ' sorted-' . $activeSortOrder : '') : '';
            @endphp
            <th
                @if($isSortable) data-sort-column="{{ $key }}" @endif
                @if($thClass) class="{{ $thClass }}" @endif
            >{{ $value }}</th>
        @endforeach

        @if($hasActions())
            <th class="actions"></th>
        @endif
    </tr>
</thead>