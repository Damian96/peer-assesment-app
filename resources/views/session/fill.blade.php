@extends('layouts.app')

@section('breadcrumbs')
    {{ Breadcrumbs::render('session.fill', $session) }}
@endsection

@section('content')
    <form class="row px-3" action="{{ url("/sessions/{$session->id}/fillin") }}" method="POST">
        @method('POST')
        @csrf

        <h3 class="">{{ $form->title }}</h3>
        @foreach($questions as $i => $q)
            @php
                $teammates = Auth::user()->teammates()->collect()
            @endphp
            <div class="offset-1 col-sm-10 col-md-10 border-dark border py-2 my-2">
                <h5>{{ $q->title }}</h5>
                @if ($q->type === 'linear-scale')
                    <div class="form-group uselect-none">
                        <span class="mx-2">{{ $q->minlbl }}<input class="ml-2" type="radio"
                                                                  required
                                                                  name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                                                  value="1"></span>
                        @for($i=2;$i<$q->max;$i++)
                            <span class="mx-2"><input type="radio"
                                                      required
                                                      name="questions[{{ $q->id }}][{{ $q->type }}][]" value="{{ $i }}"></span>
                        @endfor
                        <span class="mx-2"><input class="mr-2" type="radio"
                                                  required
                                                  name="questions[{{ $q->id }}][{{ $q->type }}][]"
                                                  value="{{ $q->max }}">{{ $q->maxlbl }}</span>
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
                        <textarea type="text" name="questions[{{ $q->id }}][{{ $q->type }}][]" class="form-control"
                                  placeholder="Write anything here..."
                                  aria-placeholder="Write anything here..."
                                  style="min-height: 50px; max-height: 150px;"></textarea>
                    </div>
                @elseif ($q->type == 'criteria')
                    <div class="form-group criteria" data-sum="0">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <th></th>
                            <th>Distribute 100 points among your teammates including yourself</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Yourself</td>
                                <td><input type="number"
                                           required
                                           aria-required="true"
                                           min="0"
                                           max="100"
                                           step="1"
                                           aria-valuemin="0"
                                           aria-valuemax="100"
                                           class="form-control"
                                           aria-invalid="false"
                                           name="questions[{{ $q->id }}][{{ $q->type }}][{{ Auth::user()->id }}]"></td>
                            </tr>
                            @foreach($teammates as $t)
                                <tr>
                                    <td>{{ $t->full_name }}</td>
                                    <td><input type="number"
                                               min="0"
                                               max="100"
                                               step="1"
                                               aria-valuemin="0"
                                               aria-valuemax="100"
                                               class="form-control"
                                               aria-invalid="false"
                                               name="questions[{{ $q->id }}][{{ $q->type }}][{{ $t->id }}]" value="0">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <span class="invalid-feedback d-block font-weight-bold"></span>
                    </div>
                @endif
            </div>
        @endforeach

        <div class="col-sm-12 col-md-12 mt-5 mb-2">
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </div>
    </form>
@endsection

@section('end_footer')
    <script type="text/javascript">
        var criteriaOnChangeHandler = function () {
            // console.debug(this);
            let group = $(this).closest('.form-group');
            let points = calculateGroupPoints(group);
            if (points > 100) {
                group.find('.invalid-feedback').text('Total points exceed 100 limit');
                $(this).removeClass('is-valid')
                    .addClass('is-invalid')
                    .attr('aria-invalid', 'true');
                this.setCustomValidity('Total points exceed 100 limit');
                return true;
            } else if (points > 0 && points < 100) {
                group.find('.invalid-feedback').text('Total points should be 100');
            }
            $(this).removeClass('is-invalid')
                .attr('aria-invalid', 'false')
                .addClass('is-valid');
            this.setCustomValidity('');
            group.find('.invalid-feedback').text('');
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
                $(this).find('.criteria').each(function () {
                    let sum = parseInt($(this).data('sum'));
                    if (sum < 100) {
                        $(this).find('.invalid-feedback').text('Total points should be 100');
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        return false;
                    }
                });
            });
            $('.form-group.criteria').find('input').on('focusout click', criteriaOnChangeHandler);
        });
    </script>
@endsection
