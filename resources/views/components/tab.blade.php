<div
    class="pj-tab-panel"
    data-label="{{ $label }}"
    @if($active) data-active="true" @endif
    role="tabpanel"
>
    {{ $slot }}
</div>
