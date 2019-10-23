@extends('layouts.error')

@php
  $error_number = 404;
@endphp

@section('title')
    <h1>Page not found.</h1>
@endsection

@section('content')
  @php
    $default_error_message = "Please <a href='javascript:history.back()''>go back</a> or return to <a href='".url('')."'>our homepage</a>.";
  @endphp
  {!! isset($exception)? ($exception->getMessage()?$exception->getMessage():$default_error_message): $default_error_message !!}
@endsection
