@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Welcome <strong>{{ $user->name }}</strong></h2>
            <table class="table table-primary">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Instructor</th>
                        <th>Registered At</th>
                        <th>Verified</th>
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
                        <td>{{ $user->registration_date }}</td>
                        <td>{{ $user->verification_date }}</td>
                        <td>{{ $user->reg_num }}</td>
                        <td>{{ $user->department_title }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
