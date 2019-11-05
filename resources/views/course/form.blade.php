{{-- method, action, $errors --}}
<form role="form" method="{{ $method == 'PUT' ? 'POST' : $method }}" action="{{ $action }}">
    @method($method)
    @csrf

    <div class="form-group">
        <label class="form-text" for="title">Title</label>
        <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="title" type="text"
               required aria-required="true" maxlength="50" aria-valuemax="50"
               placeholder="a title to display to students. e.g. 2019-CCP3300-Java"
               aria-placeholder="a title to display to students. e.g. 2019-CCP3300-Java"
               aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}" pattern="[a-zA-Z0-9\-_].*"
               value="{{ old('title', isset($course) ? $course->title : '') }}">

        @if ($errors->has('title'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('title') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <label class="form-text" for="code">Code</label>
        <input class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" id="code" type="text"
               required aria-required="true" maxlength="10" aria-valuemax="10" placeholder="e.g. CCP1903"
               aria-placeholder="e.g. CCP1903" aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}"
               pattern="[a-zA-Z0-9\-_].*" value="{{ old('code', isset($course) ? $course->code : '') }}">

        @if ($errors->has('code'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('code') }}</strong>
            </span>
        @endif
    </div>

    @if(Auth::user()->isAdmin() && (request()->route()->named('*edit') || request()->route()->named('*create')))
        <div class="form-group">
            <label class="form-text" for="instructor">Instructor</label>
            <select class="form-control{{ $errors->has('instructor') ? ' is-invalid' : '' }}" name="instructor"
                    id="instructor" required aria-required="true"
                    aria-invalid="{{ $errors->has('instructor') ? 'true' : 'false' }}">
                @foreach(App\User::getInstructors() as $item)
                    <option
                        value="{{ $item->id }}"{{ intval(old('instructor', isset($course) ? $course->instructor : '')) == $item->id ? ' selected' : ''  }}>{{ $item->name }}</option>
                @endforeach
            </select>

            @if ($errors->has('instructor'))
                <span class="invalid-feedback">
                <strong>{{ $errors->first('instructor') }}</strong>
            </span>
            @endif
        </div>
    @endif

    @if (Auth::user()->isAdmin() || Auth::user()->can('course.create') || (isset($course) && Auth::user()->can('course.edit', ['id' => $course->id])))
        <div class="form-group">
            <label for="ac_year">Academic Year</label>
            <select id="ac_year" name="ac_year" class="form-control{{ $errors->has('ac_year') ? ' is-invalid' : '' }}">
                @foreach(range(intval(date('Y')), config('constants.date.start'), -1) as $year)
                    <option
                        value="{{ $year }}"{{ $year == old('ac_year', isset($course) ? $course->ac_year_int : intval(date('Y'))) ? ' selected' : null }}>{{ $year }}</option>
                @endforeach
            </select>

            @if ($errors->has('ac_year'))
                <span class="invalid-feedback">
                <strong>{{ $errors->first('ac_year') }}</strong>
            </span>
            @endif
        </div>
    @endif

    <div class="form-group">
        <label class="form-text" for="description">Description</label>
        <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description"
                  id="description" maxlength="150"
                  placeholder="a short description of the course. e.g. level 1 course of java programming, english programme"
                  aria-placeholder="a short description of the course. e.g. level 1 course of java programming, english programme"
                  aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}">{{ old('description', isset($course) ? $course->description : '') }}</textarea>

        @if ($errors->has('description'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('description') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-block btn-primary" role="button" title="{{ $button['title'] }}"
                aria-roledescription="{{ $button['title'] }}" tabindex="0">{{ $button['label'] }}</button>
    </div>
</form>
