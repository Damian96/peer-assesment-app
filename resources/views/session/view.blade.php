@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="row">ID</th>
                    {{--                    <th>Owner</th>--}}
                    <th>Title</th>
                    <th class="">Department</th>
                    <th class="">Academic Year</th>
                    {{--                    <th class="text-right">Links</th>--}}
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">{{ $session->id }}</th>
                    {{--                    <td>{{ $session->owner_name }}</td>--}}
                    <td>{{ $session->title }}</td>
                    <td>{{ $session->department }}</td>
                    <td>{{ $session->ac_year_pair }}</td>
                </tr>
                </tbody>
                @if (Auth::user()->isAdmin())
                    <tfoot>
                    <tr>
                        <td colspan="2">Created: {{ $session->create_date }}</td>
                        <td colspan="2">Updated: {{ $session->update_date }}</td>
                        <td colspan="1">Status: {{ $session->status_full }}</td>
                    </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection
