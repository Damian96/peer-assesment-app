@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('session.index', $sessions, $course) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            @if ($sessions->isNotEmpty())
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Status</th>
                        <th>Instructions</th>
                        <th>Deadline</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sessions as $session)
                        <tr>
                            <td>{{ $session->status }}</td>
                            <td>{{ $session->instructions }}</td>
                            <td>{{ $session->deadline }}</td>
                            <td class="action-cell">
                                <a href="{{ url('/sessions/' . $session->id . '/view') }}"
                                   class="material-icons">link</a>
                                <a href="{{ url('/sessions/' . $session->id . '/edit') }}"
                                   class="material-icons text-warning">edit</a>
                                <a href="{{ url('/sessions/' . $session->id . '/delete') }}"
                                   class="material-icons text-danger">delete_forever</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                @if (Auth::user()->can('session.create', ['id' => $course->id]))
                    <h2>You do not have any Sessions yet!</h2>
                    <p>
                        <a class="btn btn-primary" href="{{ url('/sessions/create/' . $course->id) }}"
                           title="Add Session"
                           aria-roledescription="Add Session">Add Session</a>
                    </p>
                @endif
            @endif
        </div>
    </div>
@endsection
