@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>Welcome <strong>{{ $user->name }}</strong></h2>
            <table class="table table-primary">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Instructor</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->fname . $user->lname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->isInstructor() ? 'Yes' : 'No' }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
