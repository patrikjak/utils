<div class="pj-dropdown">
    @if($label !== null)
        <p class="label">{{ $label }}</p>
    @endif

    <div class="dropdown-wrapper">
        <div class="items">
            <x-pjutils::dropdown.chosen :value="$selected" :label="$items[$selected]" />

            <div class="all-items">
                @foreach($items as $key => $item)
                    <x-pjutils::dropdown.item :value="$key" :label="$item" :selected="$key === $selected" />
                @endforeach
            </div>
        </div>
    </div>
</div>