<div class="pj-toggle{{ $disabled ? ' disabled' : '' }}">
    <label class="pj-toggle-wrapper">
        <input
            type="checkbox"
            {{ $attributes->only(['name', 'id', 'value']) }}
            @checked($checked)
            @disabled($disabled)
        >
        <span class="pj-toggle-track">
            <span class="pj-toggle-thumb"></span>
        </span>
        @if($label !== null)
            <span class="pj-toggle-label">{{ $label }}</span>
        @endif
    </label>
</div>
