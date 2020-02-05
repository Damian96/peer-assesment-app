@php $departments = implode(',', config('constants.departments.short')); @endphp

{{-- method, action, $errors --}}
<form role="form" method="{{ $method == 'PUT' ? 'POST' : $method }}" action="{{ $action }}">
    @method($method)
    @csrf

    <div class="form-group">
        <label class="form-text" for="title">Title</label>
        <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="title" type="text"
               aria-required="true" maxlength="50" aria-valuemax="50"
               placeholder="a title to display to students. e.g. 2019-CCP3300-Java"
               aria-placeholder="a title to display to students. e.g. 2019-CCP3300-Java"
               aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}" pattern="[a-zA-Z0-9\-_].*"
               value="{{ old('title', isset($course) ? $course->title : '') }}"
               data-rule-required="true"
               data-msg-required="{{ $messages['title.required'] }}"
               data-rule-minlength="5"
               data-msg-minlength="{{ $messages['title.min'] }}"
               data-rule-maxlength="50"
               data-msg-maxlength="{{ $messages['title.max'] }}">

        <span class="invalid-feedback">
                <strong>{{ $errors->first('title') ?? '' }}</strong>
            </span>
    </div>

    <div class="form-group">
        <label class="form-text" for="code">Code</label>
        <input class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" id="code" type="text"
               aria-required="true" maxlength="10" aria-valuemax="10" placeholder="e.g. CCP1903"
               aria-placeholder="e.g. CCP1903" aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}"
               pattern="[a-zA-Z0-9\-_].*" value="{{ old('code', isset($course) ? $course->code : '') }}"
               data-rule-required="true"
               data-msg-required="{{ $messages['code.required'] }}"
               data-rule-minlength="5"
               data-msg-minlength="{{ $messages['code.min'] }}"
               data-rule-maxlength="10"
               data-msg-maxlength="{{ $messages['code.max'] }}">

        <span class="invalid-feedback">
            <strong>{{ $errors->first('code') ?? '' }}</strong>
        </span>
    </div>

    @if(Auth::user()->isAdmin() && (request()->route()->named('*edit') || request()->route()->named('*create')))
        <div class="form-group">
            <label class="form-text" for="instructor">Instructor</label>
            <select class="form-control{{ $errors->has('instructor') ? ' is-invalid' : '' }}" name="instructor"
                    id="instructor" aria-required="true"
                    aria-invalid="{{ $errors->has('instructor') ? 'true' : 'false' }}">
                @foreach(App\User::getInstructors() as $item)
                    <option
                        value="{{ $item->id }}"{{ intval(old('instructor', isset($course) ? $course->instructor : '')) == $item->id ? ' selected' : ''  }}>{{ $item->name }}</option>
                @endforeach
            </select>

            <span class="invalid-feedback">
                <strong>{{ $errors->first('instructor') ?? '' }}</strong>
            </span>
        </div>
    @endif

    <div class="form-group">
        <label for="department">Department</label>
        <select name="department" id="department"
                class="form-control{{ $errors->has('department') ? ' is-invalid' : '' }}"
                data-rule-required="true"
                data-msg-required="{!! $messages['department.required'] !!}"
                data-rule-in="{!! $departments !!}"
                data-msg-in="{!! $messages['department.in'] !!}"
        >
            <option
                value="admin"{{ old('department', isset($course) ? $course->department : '') == 'admin' ? 'selected' : '' }}>
                ---
            </option>
            <option
                value="CCP"{{ old('department', isset($course) ? $course->department : '') == 'CCP' ? 'selected' : '' }}>
                Computer Science
            </option>
            <option
                value="CES"{{ old('department', isset($course) ? $course->department : '') == 'CES' ? 'selected' : '' }}>
                English Studies
            </option>
            <option
                value="CBE"{{ old('department', isset($course) ? $course->department : '') == 'CBE' ? 'selected' : '' }}>
                Business Administration & Economics
            </option>
            <option
                value="CPY"{{ old('department', isset($course) ? $course->department : '') == 'CPY' ? 'selected' : '' }}>
                Psychology Studies
            </option>
            <option
                value="MBA"{{ old('department', isset($course) ? $course->department : '') == 'MBA' ? 'selected' : '' }}>
                Executive MBA
            </option>
        </select>

        <span class="invalid-feedback">
                <strong>{{ $errors->first('department') ?? '' }}</strong>
        </span>
    </div>

    @if (Auth::user()->isAdmin())
        <div class="form-group">
            <label for="ac_year">Academic Year</label>
            <select id="ac_year" name="ac_year" class="form-control{{ $errors->has('ac_year') ? ' is-invalid' : '' }}">
                @foreach(range(intval(date('Y')), config('constants.date.start'), -1) as $year)
                    <option
                        value="{{ date(config('constants.date.stamp'), strtotime(sprintf("%s-09-01",$year))) }}"{{ $year == old('ac_year', isset($course) ? $course->ac_year_int : intval(date('Y'))) ? ' selected' : null }}>{{ $year . '-' . ($year+1) }}</option>
                @endforeach
            </select>

            @if ($errors->has('ac_year'))
                <span class="invalid-feedback">
                <strong>{{ $errors->first('ac_year') }}</strong>
            </span>
            @endif
        </div>
    @elseif (Auth::user()->can('course.create') || (isset($course) && Auth::user()->can('course.edit', ['id' => $course->id])))
        <input type="hidden" class="hidden" value="{{ date('Y') }}" width="0" height="0">
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

    @if (false)
        {{--    @if (isset($course) && ! $course->copied() && Auth::user()->can('course.edit', ['course' =>  $course]))--}}
        <div class="form-group">
            <button type="submit" class="btn btn-block btn-outline-info" role="button" name="copy" id="copy"
                    title="Copy to current Academic Year"
                    aria-roledescription="Copy to current Academic Year" tabindex="0">Copy
            </button>
        </div>
    @endif
</form>

@section('end_footer')
    <script type="text/javascript" defer>
        $(document).on('focusout change', 'input, select, textarea', function () {
            return $(this).valid();
        });
        $(document).on('submit', 'form', function (event) {
            let form = $(event.target);
            form.validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },
                    code: {
                        required: true,
                        minlength: 6,
                        maxlength: 10
                    },
                    department: {
                        required: true,
                        in: "{!! $departments !!}",
                        maxlength: 10
                    },
                    description: {
                        maxlength: 150
                    }
                },
                messages: {
                    title: {
                        required: "{!! $messages['title.required'] !!}",
                        minlength: "{!! $messages['title.min'] !!}",
                        maxlength: "{!! $messages['title.max'] !!}"
                    },
                    code: {
                        required: "{!! $messages['code.required'] !!}",
                        minlength: "{!! $messages['code.min'] !!}",
                        maxlength: "{!! $messages['code.max'] !!}"
                    },
                    department: {
                        required: "{!! $messages['department.required'] !!}",
                        in: "{!! $messages['department.in'] !!}"
                    },
                    description: {
                        maxlength: "{!! $messages['description.max'] !!}"
                    }
                }
            });

            // console.log(form, form.valid());
            // event.preventDefault();
            // return false;
            return form.valid();
        });
    </script>
@endsection
