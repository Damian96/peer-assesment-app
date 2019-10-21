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

            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Instructor</th>
                    <th>Registration Number</th>
                    <th>Department</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->fname . ' ' . $user->lname }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->isInstructor() ? 'Yes' : 'No' }}</td>
                    <td>{{ $user->reg_num }}</td>
                    <td>{{ $user->department_title }}</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2">Registered: {{ $user->registration_date }}</td>
                    <td colspan="2">Verified: {{ $user->verification_date }}</td>
                    <td colspan="2">Last Login: {{ $user->last_login }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
