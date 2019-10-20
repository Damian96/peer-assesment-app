@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if (session()->has('message'))
                <div class="alert alert-{{ session()->get('message')['level'] }}" role="alert">
                    <h4 class="alert-heading">{{ session()->get('message')['heading'] }}</h4>
                    {{ session()->get('message')['body'] }}
                </div>
            @endif
            <h2>Welcome <strong>{{ $user->name }}</strong></h2>
        </div>
    </div>
@endsection
