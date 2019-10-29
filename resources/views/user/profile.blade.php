@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        @if ($user->isAdmin()) <th>ID</th> @endif
                        <th>Full Name</th>
                        <th>Email</th>
                        @if (!$user->isStudent()) <th>Role</th> @endif
                        @if ($user->isStudent()) <th>Registration Number</th> @endif
                        @if ($user->isStudent()) <th>Department</th> @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @if ($user->isAdmin()) <td>{{ $user->id }}</td> @endif
                        <td>{{ $user->fname . ' ' . $user->lname }}</td>
                        <td>{{ $user->email }}</td>
                        @if (!$user->isStudent()) <td>{{ $user->role  }}</td> @endif
                        @if ($user->isStudent()) <td>{{ $user->reg_num }}</td> @endif
                        @if ($user->isStudent()) <td>{{ $user->department_title }}</td> @endif
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">Registered: {{ $user->registration_date }}</td>
                        <td colspan="2">Verified: {{ $user->verification_date }}</td>
                        @if (!$user->isStudent()) <td colspan="2">Last Login: {{ $user->last_login }}</td> @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
