<div class="{{ $actionItemClass }}">
    @if($hasIcon)
        <div class="icon">
            @include('icons.' . $action->icon)
        </div>
    @endif

    <div class="label">
        <p>{{ $action->label }}</p>
    </div>
</div>