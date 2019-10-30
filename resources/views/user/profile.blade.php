@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        @if ($user->isAdmin())
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        @elseif ($user->isInstructor())
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        @elseif ($user->isStudent())
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Registration Number</th>
                        <th>Department</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @if ($user->isAdmin())
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role  }}</td>
                        @elseif ($user->isInstructor())
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role  }}</td>
                        @elseif ($user->isStudent())
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->reg_num }}</td>
                            <td>{{ $user->department_title }}</td>
                        @endif
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">Registered: {{ $user->registration_date }}</td>
                        <td colspan="1">Verified: {{ $user->verification_date }}</td>
                        <td colspan="1">Last Login: {{ $user->last_login }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
