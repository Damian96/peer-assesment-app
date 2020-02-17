@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('session.fill', $session) }}
@endsection

@section('content')
    <form class="row px-3" action="{{ url("/sessions/{$session->id}/fillin") }}" method="POST">
        @method('POST')
        @csrf

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-3">
            <h3 class="d-block">{{ $form->title }}</h3>
            <div class="d-block instructions">
                {{ $session->instructions }}
            </div>
        </div>
        @foreach($questions as $i => $q)
            @php
                $teammates = Auth::user()->teammates()->collect();
            @endphp
            <fieldset class="offset-1 col-sm-10 col-md-10 border-dark rounded py-2 my-2">
                <legend><i class="d-block text-muted">Question #{{ $i+1 }}</i><h4>{{ $q->title }}</h4></legend>
                @if ($q->type === 'linear-scale')
                    <div class="form-group uselect-none">
                        <span class="mx-2">{{ $q->minlbl }}<input class="ml-2" type="radio"
                                                                  id="question-{{ $q->id }}-1"
                                                                  required
                                                                  name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                                                  value="1">
                        <label class="d-none" for="{{ $q->id }}-1">{{ $q->minlbl }}</label></span>
                        @for($i=2;$i<$q->max;$i++)
                            <span class="mx-2"><input type="radio"
                                                      id="question-{{ $q->id }}-{{ $i }}"
                                                      required
                                                      name="questions[{{ $q->id }}][{{ $q->type }}][]" value="{{ $i }}">
                            <label class="d-none" for="question-{{ $q->id }}-{{ $i }}">{{ $i }}</label></span>
                        @endfor
                        <span class="mx-2"><input class="mr-2" type="radio"
                                                  id="question-{{ $q->id }}-{{ $q->max }}"
                                                  required
                                                  name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                                  value="{{ $q->max }}">{{ $q->maxlbl }}
                        <label class="d-none" for="{{ $q->id }}-{{ $q->max }}">{{ $q->maxlbl }}</label></span>
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
                @elseif ($q->type == 'criteria')
                    @php
                        $label = 'Distribute 100 points among your teammates including yourself';
                    @endphp
                    <div class="form-group criteria" data-sum="0">
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
    </form>
@endsection

@section('end_footer')
    <script type="text/javascript">
        var criteriaOnChangeHandler = function () {
            // console.debug(this);
            let group = $(this).closest('.form-group');
            let points = calculateGroupPoints(group);
            if ((points > 0 && points < 100) || points > 100) {
                group.find('.invalid-feedback').text('Total points exceed 100 limit');
                $(this).removeClass('is-valid')
                    .addClass('is-invalid')
                    .attr('aria-invalid', 'true');
                return false;
            } else {
                $(this).removeClass('is-invalid')
                    .attr('aria-invalid', 'false')
                    .addClass('is-valid');
                group.find('.invalid-feedback').text('');
                return true;
            }
        };
        var calculateGroupPoints = function (group) {
            let sum = 0;
            group.find('input').each(function () {
                sum += parseInt(this.value);
            });
            group.data('sum', sum);
            return sum;
        };
        $(function () {
            $(document).on('submit', 'form', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $('.criteria').each((i, group) => {
                    if (criteriaOnChangeHandler.call(group)) {
                        this.submit();
                        return true;
                    } else {
                        return false;
                    }
                });
                return false;
            });
            $('.form-group.criteria').find('input').on('focusout click', criteriaOnChangeHandler);
        });
    </script>
@endsection
