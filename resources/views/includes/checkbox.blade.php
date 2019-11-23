<label for="{{ $name }}"
       onclick="this.firstElementChild.value = this.firstElementChild.checked ? '1' : '0';">
    <input type="checkbox" name="{{ $name }}" id="{{ $name }}" {{ $checked ?? '' }} tabindex="0">
    <span class="ml-4">{!! $label !!}</span>
</label>
