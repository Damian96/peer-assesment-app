@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <select id="session_id"
                    onchange="(function() { $('input[name=session_id]').val(this.value) }.bind(this))();">
                @foreach($sessions as $s)
                    <option value="{{ $s->id }}">{{ $s->title }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row question-editor">
        <div class="col-sm-12 col-md-12">
            <label class="form-control-sm">Add New Question:</label>
            <button type="button" class="btn btn-large btn-dark">Yes/No Answer</button>
            <button type="button" class="btn btn-large btn-warning">Mark Answer (0-5)</button>
            <button type="button" class="btn btn-large btn-info">Text Answer</button>
        </div>
    </div>
    <form method="POST" action="{{ url('/sessions/form/store') }}">
        @method('POST')
        @csrf

        <input type="hidden" value="" name="questions"/>
        <input type="hidden" value="" name="session_id"/>
        <input type="hidden" value="" name="questions"/>

        {{--    <div class="form-group">--}}
        {{--        <label class="form-control"></label>--}}
        {{--    </div>--}}
    </form>
@endsection
