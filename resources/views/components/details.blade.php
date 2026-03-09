<dl class="pj-details">
    @foreach($rows as $label => $value)
        <x-pjutils::details.row :label="$label">{{ $value }}</x-pjutils::details.row>
    @endforeach
    {{ $slot }}
</dl>
