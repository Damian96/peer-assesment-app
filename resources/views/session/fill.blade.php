@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('session.fill', $session) }}
@endsection

@php
    $scale = [
        'Disagree',
        'Neither Agree neither Disagree',
        'Agree',
    ];
@endphp

@section('content')
    <div class="row px-3">
        <h3 class="">{{ $form->title }}</h3>
        @foreach($form->questions()->get() as $q)
            <div class="col-sm-12 col-md-12 border-dark border py-2">
                <label>{{ $q->title }}</label>
                @if ($q->type === 'linear-scale')
                    <div class="form-group">
                        <span class="mx-2">{{ $q->minlbl }}</span><input type="radio" name="{{ $q->id }}">
                        @for($i=1;$i<($q->max-1);$i++)<span class="mx-2"></span><input type="radio"
                                                                                       name="{{ $q->id }}">@endfor
                        <span class="mx-2">{{ $q->maxlbl }}</span><input type="radio" name="{{ $q->id }}">
                    </div>
                @elseif ($q->type === 'multiple-choice')
                    <div class="form-group">
                        @foreach($q->choices as $c)<span class="mx-2">{{ $c }}</span><input type="radio"
                                                                                            name="{{ $q->id }}">@endforeach
                    </div>
                @elseif ($q->type === 'eval')
                    @php
                        $teammates = Auth::user()->teammates();
                    @endphp
                    <div class="form-group">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <th></th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Yourself</td>
                                <td><input type="radio" name="{{ $q->id . Auth::user()->id }}"></td>
                                <td><input type="radio" name="{{ $q->id . Auth::user()->id }}"></td>
                                <td><input type="radio" name="{{ $q->id . Auth::user()->id }}"></td>
                                <td><input type="radio" name="{{ $q->id . Auth::user()->id }}"></td>
                                <td><input type="radio" name="{{ $q->id . Auth::user()->id }}"></td>
                            </tr>
                            @foreach($teammates as $t)
                                <tr>
                                    <td>{{ $t->lname }}</td>
                                    <td><input type="radio" name="{{ $q->id . $t->id }}"></td>
                                    <td><input type="radio" name="{{ $q->id . $t->id }}"></td>
                                    <td><input type="radio" name="{{ $q->id . $t->id }}"></td>
                                    <td><input type="radio" name="{{ $q->id . $t->id }}"></td>
                                    <td><input type="radio" name="{{ $q->id . $t->id }}"></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif ($q->type === 'paragraph')
                    <div class="form-group">
                        <textarea type="text" name="{{ $q->id }}" class="form-control"
                                  style="min-height: 50px; max-height: 150px;"></textarea>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endsection

