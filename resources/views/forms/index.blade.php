@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-12">
            @if ($forms->isNotEmpty())
                <table class="table table-striped">
                    <caption
                        class="">{{ sprintf("Showing results %s-%s of total %s Forms", $forms->firstItem(), $forms->lastItem(), $forms->total()) }}</caption>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Session</th>
                        <th>Mark</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($forms as $i => $form)
                        <tr>
                            <th scope="row">{{ ($i+1) }}</th>
                            <td>{{ strlen($form->title) > 50 ? substr($form->title, 0, 50) . '...' : $form->title }}</td>
                            <td>
                                <a href="{{ url('/sessions/' . $form->session_id . '/view' ) }}" target="_self">
                                    {{ $form->session_title }}
                                </a>
                            </td>
                            <td class="{{ $form->mark == 0 ? 'text-warning' : '' }}">{{ $form->mark == 0 ? 'Not Filled' : $form->mark }}</td>
                            <td>
                                {{--                                @if(Auth::user()->can('course.edit', ['id'=>$course->id]))--}}
                                <a href="{{ url('/forms/' . $form->id . '/edit') }}" class="material-icons"
                                   title="Update Form {{ $form->title }}"
                                   aria-label="Update Form {{ $form->title }}">edit</a>
                                {{--                                @endif--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $forms->links() }}
            @else
                <h2>You do not have own any Forms yet!</h2>
                <p>
                    <a class="btn btn-primary" href="{{ url('/forms/add/') }}"
                       title="Add Form"
                       aria-roledescription="Add Form">Add Form</a>
                </p>
            @endif
        </div>
    </div>
@endsection

@section('end_footer')
@endsection
