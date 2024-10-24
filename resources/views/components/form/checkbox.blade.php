<div class="{{ $wrapperClass }}">
    <div class="group">
        <input type="checkbox" {{ $attributes->merge(['id' => $id ?? $name ?? '']) }} name="{{ $name }}">

        <label for="{{ $name ?? '' }}">{{ $label ?? '' }}</label>
    </div>
</div>