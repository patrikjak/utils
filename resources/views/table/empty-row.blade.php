<tr class="empty-row">
    <td colspan="{{ $colspan }}">
        <div class="empty-state">
            @if($icon)
                <div class="empty-state-icon">{!! $icon !!}</div>
            @endif
            <p class="empty-state-title">{{ $title }}</p>
            @if($description)
                <p class="empty-state-description">{{ $description }}</p>
            @endif
        </div>
    </td>
</tr>
