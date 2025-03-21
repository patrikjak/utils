<div class="bulk-actions-wrapper hidden">
    <p class="selected"><span class="text">@lang('pjutils::table.selected'): </span><span class="count">0</span></p>

    <div class="actions">
        @foreach($bulkActions as $bulkAction)
            <div class="bulk-action">
                <x-pjutils::button
                    :class="$bulkAction->type->value"
                    data-action="{{ $bulkAction->action }}"
                    data-method="{{ $bulkAction->method }}"
                >
                    @if($bulkAction->icon !== null)
                        {!! $bulkAction->icon->getAsHtml() !!}
                    @endif

                    {{ $bulkAction->label }}
                </x-pjutils::button>
            </div>
        @endforeach
    </div>
</div>