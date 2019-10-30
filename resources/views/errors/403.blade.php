@php
    $error_number = 403;
@endphp

@extends('layouts.error')

@section('title')
    <h1>Error {{ $error_number }}</h1>
    <h3>Forbidden</h3>
@endsection

@section('content')
    <p>
        @php
            $default_error_message = "Please <a href='javascript:history.back()''>go back</a> or return to <a href='".url('')."'>our homepage</a>.";
        @endphp
        {!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
    </p>
@endsection
