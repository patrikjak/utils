@use('Patrikjak\Utils\Common\Enums\Icon')

<td class="actions" @isset($actionsDataAttributes) {!! $actionsDataAttributes !!} @endisset>
    <div class="actions-inner">
        @foreach($inlineActions as $action)
            @php
                $isHidden = in_array($action->classId, $hiddenInlineActionIds, true);
                $icon = $action->icon;
                $btnClass = implode(' ', array_filter([
                    'action-btn',
                    $action->classId,
                    $action->type->value,
                    $isHidden ? 'hidden-action' : null,
                ]));
                $resolvedHref = $inlineActionHrefs[$action->classId] ?? null;
                $isLinkAction = $resolvedHref !== null && ($action->method === null || strtolower($action->method) === 'get');
            @endphp
            @if($isLinkAction)
                <a href="{{ $resolvedHref }}" class="{{ $btnClass }}">
                    @if($icon !== null)
                        <div class="icon {{ $icon }}-icon">
                            @if($icon instanceof Icon)
                                {!! $icon->getAsHtml() !!}
                            @else
                                @customIcon($icon)
                            @endif
                        </div>
                    @endif
                    <span class="label">{{ $action->label }}</span>
                </a>
            @else
                <button type="button" class="{{ $btnClass }}">
                    @if($icon !== null)
                        <div class="icon {{ $icon }}-icon">
                            @if($icon instanceof Icon)
                                {!! $icon->getAsHtml() !!}
                            @else
                                @customIcon($icon)
                            @endif
                        </div>
                    @endif
                    <span class="label">{{ $action->label }}</span>
                </button>
            @endif
        @endforeach

        @if($hasDropdownActions)
            <div class="hellip">
                <div></div>
                <div></div>
                <div></div>
            </div>
        @endif
    </div>
</td>
