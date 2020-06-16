@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-responsive-sm">
                <thead>
                <tr>
                    @if ($user->isAdmin())
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    @elseif ($user->isInstructor())
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    @elseif ($user->isStudent())
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Registration Number</th>
                        <th>Department</th>
                    @endif
                </tr>
                </thead>
                <tbody>
                <tr>
                    @if ($user->isAdmin())
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role  }}</td>
                    @elseif ($user->isInstructor())
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role  }}</td>
                    @elseif ($user->isStudent())
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->reg_num }}</td>
                        <td>{{ $user->department_title }}</td>
                    @endif
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="2">Registered: {{ $user->registration_date }}</td>
                    <td colspan="1">Verified: {{ $user->verification_date }}</td>
                    <td colspan="1">Last Login: {{ $user->last_login_full }}</td>
                </tr>
                </tfoot>
            </table>
        </div>
        @if (Auth::user()->isAdmin() || Auth::user()->isInstructor())
            <div class="col-sm-12 col-md-12">
                <hr>
                <h4>Mark Configuration</h4>
                <form action="{{ url('/config/store') }}" method="POST">
                    @method('PUT')
                    @csrf

                    <div class="form-group">
                        <label for="fudge">Fudge Factor</label>
                        <p class="form-text text-muted">Before each students score is calculated, there’s one more
                            bit of information that is needed; how many students were in the group, and how many of them
                            submitted marks.<br>To compensate, the system calculates a multiplication factor to bring
                            the total number of submissions back up to 100% of the group. This value is identified as
                            the
                            <i>fudge factor</i>. The fudge factor is ignored if there is total participation is a
                            Session's submission.</p>
                        <input id="fudge" name="fudge"
                               class="form-control{{ $errors->has('fudge') ? ' is-invalid' : null }}"
                               type="number" step=".15" min=".5" max="2"
                               value="{{ old('fudge', $user->config->fudge_factor) }}"
                               placeholder="Default: {{ config('mark.fudge') }}"
                               aria-placeholder="Default: {{ config('mark.fudge') }}"
                               required aria-required="true"
                               title="Fudge Factor" aria-label="Fudge Factor">
                        <span
                            class="invalid-feedback d-block">{{ $errors->has('fudge') ? $errors->first('fudge') : '' }}</span>
                    </div>

                    <div class="form-group">
                        <label for="group-weight">Group Mark Weight</label>
                        <p class="form-text text-muted">The weight of the <i>Group's</i> mark in the final calculation
                            of every individual's mark.</p>
                        <input type="number" name="group"
                               class="form-control{{ $errors->has('domain') ? ' is-invalid' : null }}"
                               step=".1" min=".5" max="1" value="{{ old('group', $user->config->group_weight) }}"
                               placeholder="Default: {{ config('mark.group') }}"
                               aria-placeholder="Default: {{ config('mark.group') }}"
                               required aria-required="true"
                               title="Group Mark Weight" aria-label="Group Mark Weight">
                        <span
                            class="invalid-feedback d-block">{{ $errors->has('group') ? $errors->first('group') : '' }}</span>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Update</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
@endsection

@section('end_footer')
    @if (Auth::user()->isAdmin())
        <script type="text/javascript" defer>
            $(function () {
                $(document).on('change', 'input', function (e) {
                    if (!e.target.checkValidity()) {
                        $(e.target).addClass('is-invalid').removeClass('is-valid');
                        $(e.target).siblings('span').text(e.target.validationMessage);
                    } else {
                        $(e.target).removeClass('is-invalid').addClass('is-valid');
                        $(e.target).siblings('span').text('');
                    }
                });
            });
        </script>
    @endif
@endsection
