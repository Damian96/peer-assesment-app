@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('session.active', $sessions) }}
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            {{--            <legend>There are {{ count($sessions) }} sessions active.</legend>--}}
            <table class="table table-striped">
                <caption
                    class="">{{ sprintf("Showing results %s-%s of total %s Sessions", $sessions->firstItem(), $sessions->lastItem(), $sessions->total()) }}</caption>
                <thead>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Course</th>
                    <th>Deadline</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($sessions as $k => $s)
                    <tr>
                        <th scope="row">{{ ($sessions->firstItem()+$k) }}</th>
                        @if($s->course)
                            <td>
                                <a href="{{ url('courses/' . $s->course->id . '/view') }}"
                                   title="{{ $s->course->code }}">
                                    {{ $s->course->code }}
                                </a>
                            </td>
                        @else
                            <td class="text-muted">N/A</td>
                        @endif
                        <td>{{ $s->deadline_full }}</td>
                        <td>
                            @if(Auth::user()->can('session.edit', ['id'=>$s->course_id]))
                                <a href="{{ url('/sessions/' . $s->id . '/edit') }}"
                                   class="material-icons"
                                   title="Update Course {{ $s->title }}"
                                   aria-label="Update Course {{ $s->title }}">edit</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $sessions->links() }}
        </div>
    </div>
@endsection
