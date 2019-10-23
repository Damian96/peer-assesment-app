@extends('layouts.app')

@section('content')
    @php
        /** @var \App\Models\Course $course */
        $attributes = [
            'title' => request('title', false) !== false ? request('title') : $course->title,
            'code' => request('code', false) !== false ? request('code') : $course->code,
            'instructor' => request('instructor', false) !== false ? request('instructor') : $course->instructor,
            'description' => request('description', false) !== false ? request('description') : $course->description
        ];
    @endphp
    <div class="row">
        @if (session()->has('message'))
            <div class="alert alert-{{ session()->get('message')['level'] }}" role="alert">
                <h4 class="alert-heading">{{ session()->get('message')['heading'] }}</h4>
                {{ session()->get('message')['body'] }}
            </div>
        @endif
        <div class="col-md-8 offset-md-2">
            <form role="form" method="POST" action="{{ url('/course/edit/' . request('id', 0)) }}">
                @method('POST')
                @csrf

                <div class="form-group">
                    <label class="form-text" for="title">Title</label>
                    <input class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" id="title" type="text" required aria-required="true" maxlength="50" aria-valuemax="50" placeholder="a title to display to students. e.g. 2019-CCP3300-Java" aria-placeholder="a title to display to students. e.g. 2019-CCP3300-Java" aria-invalid="{{ $errors->has('title') ? 'true' : 'false' }}" pattern="[a-zA-Z0-9\-_].*" value="{{ $attributes['title'] }}">

                    @if ($errors->has('title'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('title') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-text" for="title">Code</label>
                    <input class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" id="code" type="text" required aria-required="true" maxlength="10" aria-valuemax="10" placeholder="e.g. CCP1903" aria-placeholder="e.g. CCP1903" aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}" pattern="[a-zA-Z0-9\-_].*" value="{{ $attributes['code'] }}">

                    @if ($errors->has('code'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('code') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-text" for="instructor">Instructor</label>
                    <select class="form-control{{ $errors->has('instructor') ? ' is-invalid' : '' }}" name="instructor" id="instructor" required aria-required="true" aria-invalid="{{ $errors->has('instructor') ? 'true' : 'false' }}">
                        @foreach(App\User::getInstructors() as $item)
                            <option value="{{ $item->id }}"{{  $attributes['instructor'] == $item->id ? ' selected' : ''  }}>{{ $item->name }}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('instructor'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('instructor') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="form-text" for="description">Description</label>
                    <textarea class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="description" id="description" maxlength="150" placeholder="a short description of the course. e.g. level 1 course of java programming, english programme" aria-placeholder="a short description of the course. e.g. level 1 course of java programming, english programme" aria-invalid="{{ $errors->has('code') ? 'true' : 'false' }}">{{ $attributes['description'] }}</textarea>

                    @if ($errors->has('code'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('code') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection
