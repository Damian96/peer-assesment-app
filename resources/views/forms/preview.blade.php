@extends('layouts.app')

@section('content')
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
        <h3 class="d-block">{{ $form->title }}</h3>
    </div>
    @foreach($questions as $i => $q)
        <fieldset class="offset-1 col-sm-10 col-md-10 border-dark rounded py-2 my-2">
            <legend><i class="d-block text-muted">Question #{{ $i+1 }}</i><h4>{{ $q->title }}</h4></legend>
            @if ($q->type === 'likert-scale')
                <div class="form-group uselect-none">
                        <span class="mx-2">
                            <label class="" for="question-{{ $q->id }}-1">Strongly Agree</label>
                            <input class="ml-2" type="radio"
                                   id="question-{{ $q->id }}-1"
                                   required
                                   name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                   value="1">
                        </span>
                    <span class="mx-2">
                            <label class="" for="question-{{ $q->id }}-2">Agree</label>
                            <input class="ml-2" type="radio"
                                   id="question-{{ $q->id }}-2"
                                   required
                                   name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                   value="2">
                        </span>
                    <span class="mx-2">
                            <label class="" for="question-{{ $q->id }}-3">Neutral</label>
                            <input class="ml-2" type="radio"
                                   id="question-{{ $q->id }}-3"
                                   required
                                   name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                   value="3">
                        </span>
                    <span class="mx-2">
                            <label class="" for="question-{{ $q->id }}-4">Disagree</label>
                            <input class="ml-2" type="radio"
                                   id="question-{{ $q->id }}-4"
                                   required
                                   name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                   value="4">
                        </span>
                    <span class="mx-2">
                            <label class="" for="question-{{ $q->id }}-5">Strongly Disagree</label>
                            <input class="ml-2" type="radio"
                                   id="question-{{ $q->id }}-4"
                                   required
                                   name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                   value="5">
                        </span>
                </div>
            @elseif ($q->type === 'multiple-choice')
                <div class="form-group">
                    @foreach($q->choices as $j => $c)
                        <span class="mx-2"><input type="radio"
                                                  name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                                  value="{{ $j }}"
                                                  class="mr-2">{{ $c }}</span><br>
                    @endforeach
                </div>
            @elseif ($q->type === 'eval')
                <div class="form-group">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <th>#</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Yourself</td>
                            <td><input type="radio"
                                       required
                                       name="questions[{{ $q->id }}][{{ $q->type }}][{{ Auth::user()->id }}]"
                                       value="1"></td>
                            <td><input type="radio"
                                       required
                                       name="questions[{{ $q->id }}][{{ $q->type }}][{{ Auth::user()->id }}]"
                                       value="2"></td>
                            <td><input type="radio"
                                       required
                                       name="questions[{{ $q->id }}][{{ $q->type }}][{{ Auth::user()->id }}]"
                                       value="3"></td>
                            <td><input type="radio"
                                       required
                                       name="questions[{{ $q->id }}][{{ $q->type }}][{{ Auth::user()->id }}]"
                                       value="4"></td>
                            <td><input type="radio"
                                       required
                                       name="questions[{{ $q->id }}][{{ $q->type }}][{{ Auth::user()->id }}]"
                                       value="5"></td>
                        </tr>
                        @foreach($teammates as $t)
                            <tr>
                                <td>{{ $t->full_name }}</td>
                                <td><input type="radio"
                                           required
                                           name="questions[{{ $q->id }}][{{ $q->type }}][{{ $t->id }}]" value="1">
                                </td>
                                <td><input type="radio"
                                           required
                                           name="questions[{{ $q->id }}][{{ $q->type }}][{{ $t->id }}]" value="2">
                                </td>
                                <td><input type="radio"
                                           required
                                           name="questions[{{ $q->id }}][{{ $q->type }}][{{ $t->id }}]" value="3">
                                </td>
                                <td><input type="radio"
                                           required
                                           name="questions[{{ $q->id }}][{{ $q->type }}][{{ $t->id }}]" value="4">
                                </td>
                                <td><input type="radio"
                                           required
                                           name="questions[{{ $q->id }}][{{ $q->type }}][{{ $t->id }}]" value="5">
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif ($q->type === 'paragraph')
                <div class="form-group">
                    <label class="d-none" for="question-{{ $q->id }}">{{ $q->title }}</label>
                    <textarea type="text" name="questions[{{ $q->id }}][{{ $q->type }}][]" class="form-control"
                              id="question-{{ $q->id }}"
                              placeholder="Write anything here..."
                              aria-placeholder="Write anything here..."
                              style="min-height: 50px; max-height: 150px;"></textarea>
                </div>
            @elseif ($q->type === 'criterion')
                @php
                    $label = 'Distribute 100 points among your teammates including yourself';
                @endphp
                <div class="form-group criterion" data-sum="0">
                    <table class="table table-bordered table-striped">
                        <caption>{{ $label }}</caption>
                        <thead>
                        <th>Student</th>
                        <th>Mark</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Yourself</td>
                            <td><input type="number"
                                       id="question-{{ $q->id }}-{{ Auth::user()->id }}"
                                       required
                                       aria-required="true"
                                       min="0"
                                       max="100"
                                       step="1"
                                       aria-valuemin="0"
                                       aria-valuemax="100"
                                       class="form-control"
                                       aria-invalid="false"
                                       name="questions[{{ $q->id }}][{{ $q->type }}][{{ Auth::user()->id }}]">
                                <label for="question-{{ $q->id }}-{{ Auth::user()->id }}"
                                       class="d-none">{{ $label }}</label>
                            </td>
                        </tr>
                        @foreach($teammates as $t)
                            <tr>
                                <td>{{ $t->full_name }}</td>
                                <td><input type="number"
                                           id="question-{{ $q->id }}-{{ $t->id }}"
                                           min="0"
                                           max="100"
                                           step="1"
                                           aria-valuemin="0"
                                           aria-valuemax="100"
                                           class="form-control"
                                           aria-invalid="false"
                                           name="questions[{{ $q->id }}][{{ $q->type }}][{{ $t->id }}]">
                                    <label class="d-none"
                                           for="question-{{ $q->id }}-{{ $t->id }}">{{ $label }}</label>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <span class="invalid-feedback d-block font-weight-bold"></span>
                </div>
            @endif
        </fieldset>
    @endforeach

    <div class="col-sm-12 col-md-12 mt-5 mb-2">
        <button type="submit" class="btn btn-primary btn-block">Submit</button>
    </div>
@endsection
