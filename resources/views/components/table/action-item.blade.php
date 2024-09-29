<div class="{{ $actionItemClass }}">
    @if($hasIcon)
        <div class="icon">
            @include('icons.' . $action->getIcon())
        </div>
    @endif

    <div class="label">
        <p>{{ $action->getLabel() }}</p>
    </div>
</div>