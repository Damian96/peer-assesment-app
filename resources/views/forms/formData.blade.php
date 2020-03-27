<div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required aria-required="true" maxlength="255"
           value="{{ old('title', false) ? old('title') : ($form->title ?? '') }}"
           placeholder="The form's main title" class="form-control{{ $errors->has('title') ? ' is-invalid' : null }}">
    <span class="invalid-feedback"><strong>{{ $errors->first('title') ?? '' }}</strong></span>
</div>
<div class="form-group">
    <label for="subtitle">Subtitle <span
            class="text-muted">(leave blank for none)</span></label>
    <input type="text" name="subtitle" id="subtitle" maxlength="255" placeholder="The form's main subtitle"
           value="{{ old('subtitle', false) ? old('subtitle') : ($form->subtitle ?? '') }}"
           class="form-control{{ $errors->has('subtitle') ? ' is-invalid' : null }}">
    <span
        class="invalid-feedback{{ $errors->has('subtitle') ? ' d-block' : null }}"><strong>{{ $errors->first('subtitle') ?? '' }}</strong></span>
</div>
