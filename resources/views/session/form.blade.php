{{-- method, action, $errors --}}
<form role="form" method="{{ $method == 'PUT' ? 'POST' : $method }}" action="{{ $action }}">
    @method($method)
    @csrf

    <div class="form-group">
        <label class="form-text" for="instructions">Instructions</label>
        <textarea class="form-control{{ $errors->has('instructions') ? ' is-invalid' : '' }}" name="instructions"
                  id="instructions" maxlength="500"
                  placeholder="a short description of the session"
                  aria-placeholder="a short description of the session"
                  aria-invalid="{{ $errors->has('instructions') ? 'true' : 'false' }}"
                  style="min-height: 100px; max-height: 150px;">{{ old('instructions', isset($session) ? $session->instructions : '') }}</textarea>

        @if ($errors->has('instructions'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('instructions') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <label for="status" onclick="this.firstElementChild.value = this.firstElementChild.checked ? '1' : '0';">
            <input type="checkbox" name="status" id="status" tabindex="0" class="form-control-feedback {{ $errors->has('instructions') ? ' is-invalid' : '' }}">
            <span class="ml-4">Disabled<span class="ml-2 text-muted">(will not send any mail)</span></span>
        </label>
        @if ($errors->has('status'))
            <span class="invalid-feedback d-block">
                <strong>{{ $errors->first('status') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <label for="deadline">
            <span class="mr-2">Deadline</span>
            <input type="text" id="deadline" name="deadline" readonly aria-readonly="true" class="form-control {{ $errors->has('deadline') ? ' is-invalid' : '' }}">
        </label>
        @if ($errors->has('deadline'))
            <span class="invalid-feedback d-block">
                <strong>{{ $errors->first('deadline') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-block btn-primary" role="button" title="{{ $button['title'] }}"
                aria-roledescription="{{ $button['title'] }}" tabindex="0">{{ $button['label'] }}</button>
    </div>
</form>

@section('end_footer')
    <script type="text/javascript" defer>
        $(document).ready(function () {
            $("#deadline").datepicker({
                dateFormat: 'mm-dd-yy',
                minDate: 1,
                maxDate: 6*31,
                defaultDate: '{{ old('deadline', 1) }}',
            });
            $('#deadline').datepicker('setDate', '{{ old('deadline', null) }}');
        });
    </script>
@endsection
