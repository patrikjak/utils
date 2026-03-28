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
            >
                {{ $value }}
                @if($isSortable)
                    <span class="sort-icon">
                        @if($isActive && $activeSortOrder === 'asc')
                            <x-heroicon-o-sort-ascending class="sort-icon-svg" />
                        @elseif($isActive && $activeSortOrder === 'desc')
                            <x-heroicon-o-sort-descending class="sort-icon-svg" />
                        @else
                            <x-heroicon-o-switch-vertical class="sort-icon-svg" />
                        @endif
                    </span>
                @endif
            </th>
        @endforeach

        @if($hasActions())
            <th class="actions"></th>
        @endif
    </tr>
</thead>