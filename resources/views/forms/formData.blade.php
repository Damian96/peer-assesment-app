<div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required aria-required="true" maxlength="255"
           value="{{ old('title', false) ? old('title') : ( isset($form) ? $form->title : null) }}"
           placeholder="The form's main title" class="form-control">
    <span class="invalid-feedback"><strong>{{ $errors->first('title') ?? '' }}</strong></span>
</div>
<div class="form-group">
    <label for="subtitle">Subtitle <span
            class="text-muted">(leave blank for none)</span></label>
    <input type="text" name="subtitle" id="subtitle" maxlength="255" placeholder="The form's main subtitle"
           value="{{ old('subtitle', false) ? old('subtitle') : (isset($form) ? $form->subtitle : null) }}"
           class="form-control{{ $errors->first('subtitle') ?? 'is-invalid' }}">
    <span
        class="invalid-feedback {{ $errors->first('subtitle') ?? 'd-block' }}"><strong>{{ $errors->first('subtitle') ?? '' }}</strong></span>
</div>
<div class="form-group">
    <label for="footnote">Footer Note <span
            class="text-muted">(leave blank for none)</span></label>
    <input type="text" name="footnote" id="footnote" maxlength="255" placeholder="The form's Foot Note"
           value="{{ old('footnote', false) ? old('footnote') : (isset($form) ? $form->footnote : null) }}"
           class="form-control">
    <span class="invalid-feedback"><strong>{{ $errors->first('footnote') ?? '' }}</strong></span>
</div>
