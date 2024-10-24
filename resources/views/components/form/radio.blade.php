<div class="{{ $wrapperClass }}">
    <div class="group">
        <input type="radio"
               name="{{ $name }}"
               id="{{ $id }}"
               @checked($checked)
               @disabled($disabled)
               @if(isset($value)) value="{{$value}}" @endif
        >
        <label for="{{ $id }}">{{ $label }}</label>
    </div>
</div>
