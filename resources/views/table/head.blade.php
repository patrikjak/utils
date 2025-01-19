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
            <th>{{ $value }}</th>
        @endforeach

        @if($hasActions())
            <th class="actions"></th>
        @endif
    </tr>
</thead>