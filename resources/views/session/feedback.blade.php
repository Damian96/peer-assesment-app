@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <h3 class="d-block">{{ $form->title }}</h3>
            <div class="d-block text-info">
                Answers of Student <a href="{{ url('/users/' . $student->id . '/show') }}">{{ $student->full_name }}</a>
                <br>
                <button id="export-png" type="button" class="btn btn-primary"><i class="material-icons">image</i>Export
                    to Image
                </button>
            </div>
        </div>
    </div>
    <div id="question-container">
        @foreach($questions as $i => $question)
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 d-flex">
                    @php
                        /**
                         * @var Question $question
                         * @var User $student
                         */
                        $q = \App\Review::whereQuestionId($question->id)->where('sender_id', '=', $student->id);
                        $review = $q->first();
                    @endphp
                    <fieldset class="offset-1 col-sm-10 col-md-10 border-dark rounded py-2 my-2">
                        <legend><i class="d-block text-muted">Question #{{ $i+1 }}</i><h4>{{ $question->title }}</h4>
                        </legend>
                        @switch($review->type)
                            @case('r')
                            <table class="col-xs-12 col-sm-12 col-md-12 table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Student</th>
                                    <th class="text-center">Mark</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Yourself</td>
                                    <td class="text-center">{{ $q->where('recipient_id', '=', $student->id)->first()->mark }}</td>
                                </tr>
                                @foreach($student->teammates()->collect() as $user)
                                    <tr>
                                        <td>{{ $user->full_name }}</td>
                                        <td class="text-center">{{ $review->mark }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @break
                            @case('s')
                            <table class="col-xs-12 col-sm-12 col-md-12 table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>Question</th>
                                    <th colspan="5"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th scope="row">{{ $question->title }}</th>
                                    <td>Strongly Agree
                                        <input type="radio" disabled aria-disabled="true"
                                               class="form-control-sm" {{ $review->mark == 1 ? ' checked' : null }}">
                                    </td>
                                    <td>Agree
                                        <input type="radio" disabled aria-disabled="true"
                                               class="form-control-sm" {{ $review->mark == 2 ? ' checked' : null }}">
                                    </td>
                                    <td>Neutral
                                        <input type="radio" disabled aria-disabled="true"
                                               class="form-control-sm" {{ $review->mark == 3 ? ' checked' : null }}">
                                    </td>
                                    <td>Disagree
                                        <input type="radio" disabled aria-disabled="true"
                                               class="form-control-sm" {{ $review->mark == 4 ? ' checked' : null }}">
                                    </td>
                                    <td>Strongly Disagree
                                        <input type="radio" disabled aria-disabled="true"
                                               class="form-control-sm" {{ $review->mark == 5 ? ' checked' : null }}">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            @break
                            @case('p')
                            <div
                                class="form-text bg-gray text-white p-2">{{ !empty($q->comment) ? $q->comment : 'N/A' }}</div>
                            @break
                        @endswitch
                    </fieldset>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('end_footer')
    <script type="text/javascript" defer>
        $(function () {
            $('#export-png').click(function () {
                $('button').addClass('d-none');
                html2canvas(document.querySelector('main'))
                    .then(canvas => {
                        canvas.toBlob(function (blob) {
                            let url = URL.createObjectURL(blob);
                            let a = document.createElement('a');
                            a.href = url;
                            a.download = "{{ $form->title . '-' . $student->lname }}";
                            a.click();
                        });
                    })
                    .finally(() => {
                        $('button').removeClass('d-none');
                    });
            });
        });
    </script>
@endsection
