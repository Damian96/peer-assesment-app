<label id="lbl-{{ $name }}" for="{{ $name }}"
       onclick="(function() { this.firstElementChild.value = this.firstElementChild.checked ? '1' : '0'; }.bind(this))();">
    <input type="checkbox" name="{{ $name }}" id="{{ $name }}" {{ $checked ?? '' }} tabindex="0"
           aria-labelledby="lbl-{{ $name }}" value="{{ $value }}">
    <span class="ml-4">{!! $label !!}</span>
</label>
