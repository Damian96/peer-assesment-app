<?php

namespace App\Http\Controllers;

use App\Course;
use App\Form;
use App\Group;
use App\Question;
use App\Review;
use App\Rules\DateCompare;
use App\Rules\UniqueCombo;
use App\Session;
use App\StudentGroup;
use App\StudentSession;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SessionController extends Controller
{
    const PER_PAGE = 10;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('guest');
        $this->middleware('role');
        $this->middleware('verified');
    }

    /**
     * @param string $action
     * @return array
     */
    public function rules(string $action)
    {
        $rules = [
            'instructions' => 'required|string|max:1000',
            'open_date' => 'required|different:deadline|date_format:d-m-Y',
            'deadline' => [
                'required', 'date_format:d-m-Y', new DateCompare(request(), 'open_date', '>')
            ]
        ];

        switch ($action) {
            case 'joinGroup':
                return [
                    '_method' => 'bail|required|in:POST',
                    'session_id' => 'bail|required|min:1|integer|exists:sessions,id',
                    'group_id' => 'bail|required|min:1|integer|exists:groups,id',
                ];
            case 'addGroup':
                return [
                    '_method' => 'bail|required|in:POST',
                    'session_id' => 'bail|required|min:1|integer|exists:sessions,id',
                    'title' => [
                        'required', 'min:10', 'max:255', new UniqueCombo(request(), 'groups', 'name', 'session_id')
                    ],
                ];
            case 'fillin':
                return [
                    '_method' => 'required|in:POST',
                ];
            case 'create':
            case 'store':
                return array_merge($rules, [
                    'course' => 'required|numeric|exists:courses,id',
                    'title' => 'required|string|min:3|max:50',
                ]);
            case 'edit':
            case 'update':
                return array_merge($rules, [
                    'form' => 'required|numeric|exists:forms,id',
                    'course' => 'required|numeric|exists:courses,id',
                    'title' => 'required|string|min:3|max:50',
                    'instructions' => 'required|string|max:1000',
                    'open_date' => 'required|different:deadline|date_format:d-m-Y',
                    'deadline' => [
                        'required', 'date_format:d-m-Y', new DateCompare(request(), 'open_date', '>')
                    ]
                ]);
            default:
                return [];
        }
    }

    /**
     * @param string $action
     * @return array
     */
    public function messages(string $action)
    {
        switch ($action) {
            case 'addGroup':
                return [
                    'title.required' => 'The Group should have a title!',
                    'title.min' => 'The Group\'s title should be at least 10 characters long!',
                    'title.max' => 'The Group\'s title should be at most 255 characters long!',
                    'title.unique' => 'The Group\'s title should be at most 255 characters long!',
                ];
            case 'create':
            case 'store':
            case 'edit':
            case 'update':
                return [
                    'form.required' => 'The target Form is required!',
                    'form.numeric' => 'Invalid Form!',
                    'form.exists' => 'Invalid Form!',
                    'course.required' => 'The target Course is required!',
                    'course.numeric' => 'Invalid Course!',
                    'course.exists' => 'Invalid Course!',
                    'title.required' => 'The Session title is required!',
                    'title.min' => 'The title should be at least 3 characters!',
                    'title.max' => 'The title should not exceed 50 characters!',
                    'instructions.required' => 'The Session should have instructions!',
                    'instructions.max' => 'The Session instructions should not exceed 1000 characters!',
                    'deadline.required' => 'The Session deadline is required!',
                    'deadline.date_format' => 'The Session deadline should have a valid format!',
                    'open_date.required' => 'The Session open_date is required!',
                    'open_date.date_format' => 'The Session open_date should have a valid format!',
                ];
            default:
                return [];
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        $user = Auth::user();
        $sessions = Session::all()->collect();

        return response(view('session.all', compact('title', 'sessions')), 200, $request->headers->all());
    }

    /**
     * Display a listing of the resource, filtered by the specified Course.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'My Sessions';
        $submitted = StudentSession::whereUserId(Auth::user()->id);
        $sessions = Session::query()
            ->select('sessions.*')
            ->leftJoin('courses', 'courses.id', 'sessions.course_id');
        if (Auth::user()->isStudent()) {
            $sessions->join('student_course', 'student_course.course_id', 'courses.id')
                ->whereNotIn('sessions.id', $submitted->exists() ? $submitted->get(['session_id'])->toArray() : [])
                ->where('student_course.user_id', '=', Auth::user()->id);
        } elseif (Auth::user()->isInstructor()) {
            $sessions->where('courses.user_id', '=', Auth::user()->id);
        }
        $sessions = $sessions->where('sessions.id', '!=', Course::DUMMY_ID)->paginate(self::PER_PAGE);
        return response(view('session.index', compact('title', 'sessions')), 200, $request->headers->all());
    }

    /**
     * @method GET
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    public function create(Request $request, Course $course)
    {
        $forms = Auth::user()->forms()->concat(Form::whereSessionId(0)->get());
        $courses = Course::getCurrentYears()->where('user_id', '=', Auth::user()->id)->get();
        if ($course instanceof Course && $course->code) {
            $title = 'Create Session - ' . $course->code;
        } else {
            $course = null;
            $title = 'Create Session';
        }
        $messages = $this->messages(__FUNCTION__);
        return \response(view('session.create', compact('title', 'course', 'forms', 'messages', 'courses')), 200, $request->headers->all());
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function edit(Request $request, Session $session)
    {
        $title = 'Edit Session ' . $session->title;
        $owned = $session->form()->exists() ? [$session->form()->getModel()] : [];
        $temps = Form::whereSessionId(0)->get('forms.*')->all();
        $forms = array_merge($owned, $temps);
        $messages = $this->messages(__FUNCTION__);
        $courses = Course::getCurrentYears()->get();

        return response(view('session.edit', compact('courses', 'title', 'session', 'messages', 'forms')));
    }

    /**
     * @method POST
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            return redirect()->back(302, $request->headers->all())
                ->withInput($request->input())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        $request->request->add([
            'course_id' => $request->get('course', false),
            'deadline' => Carbon::createFromTimestamp(strtotime($request->get('deadline', date(config('constants.date.stamp')))))->format(config('constants.date.stamp')),
            'open_date' => Carbon::createFromTimestamp(strtotime($request->get('open_date', date(config('constants.date.stamp')))))->format(config('constants.date.stamp'))
        ]);
        $session = new Session($request->all());
        if ($session->save()) {
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => sprintf("Session %s has been saved successfully!", $session->id),
            ]);
            $session->sendEmailNotification();
            return redirect()->action('SessionController@show', ['session' => $session], 302);
        }

        abort_if(env('APP_DEBUG', false), 500, sprintf("Could not save Session %s!", $session->id));
        return redirect()->back(302)
            ->withInput($request->all());
    }

    /**
     * @method POST
     * @param Request $request
     * @param Session $session
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(Request $request, Session $session)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            return redirect()->back(302, $request->headers->all())
                ->withInput($request->input())
                ->withHeaders($request->headers->all())
                ->with('errors', $validator->errors());
        }

        $now = Carbon::now(config('app.timezone'))
            ->format(config('constants.date.stamp'));
        $request->request->add([
            'course_id' => $request->get('course', false),
            'deadline' => Carbon::createFromTimestamp(strtotime($request->get('deadline', $now)), config('app.timezone'))
                ->format(config('constants.date.stamp')),
            'open_date' => Carbon::createFromTimestamp(strtotime($request->get('open_date', $now)), config('app.timezone'))
                ->format(config('constants.date.stamp')),
        ]);
        if ($request->get('form', 0) == 1) {
            $form = Form::find(1)->replicate()->fill([
                'session_id' => $session->id,
                'mark' => '0',
            ]);

            if (!$form->save()) {
                throw_if(env('APP_DEBUG', false), 500, sprintf("Could not update Session %s!", $session->id));
                return redirect()->back(302, $request->headers->all());
            }
        }
        if ($session->update($request->except(['course', '_token', '_method', 'form']))) {
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => sprintf("Session %s has been saved successfully!", $session->id),
            ]);
            $session->sendEmailNotification();
            return redirect()->back(302, $request->headers->all());
        }

        throw_if(env('APP_DEBUG', false), 500, sprintf("Could not update Session %s!", $session->id));
        return redirect()->back(302, $request->headers->all());
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return Response
     */
    public function show(Request $request, Session $session)
    {
        $title = 'View Session ' . $session->title;
        return response(view('session.view', compact('title', 'session')));
    }

    /**
     * @method _DELETE
     * @param Request $request
     * @param Session $session
     * @return Response|RedirectResponse
     * @throws \Throwable
     */
    public function delete(Request $request, Session $session)
    {
        if ($session->groups()->exists()) {
            $request->session()->flash('message', [
                'level' => 'warning',
                'heading' => 'Cannot delete Session!',
                'body' => 'This Session has other Group(s) associated, so it cannot be deleted.'
            ]);
            return redirect()->back(302);
        }

        if ($session->delete()) {
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => sprintf("Successfully delete Session: %s", $session->title),
            ]);
            return redirect()->back(302, [], false);
        }

        throw_if(env('APP_DEBUG', false), 500, 'Could not delete Session ' . $session->id);
        $request->session()->flash('message', [
            'level' => 'danger',
            'heading' => sprintf("Could not delete Session: %s", $session->title),
        ]);
        return redirect()->back(302, [], false);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return Response|RedirectResponse
     * @throws \Throwable
     */
    public function fill(Request $request, Session $session)
    {
        $title = sprintf("Fill Session %s", $session->title);

        throw_if(!$session->form()->exists(), new NotFoundHttpException("This Session does not have an associated Form!"));
        $form = $session->form()->first();
        $questions = $form->questions()->getResults();

        return response(view('session.fill', compact('title', 'questions', 'form', 'session')), 200, $request->headers->all());
    }

    /**
     * @method _POST
     * @param Request $request
     * @param Session $session
     * @return Response|RedirectResponse
     * @throws \Throwable
     */
    public function fillin(Request $request, Session $session)
    {
        $validator = Validator::make($this->rules(__FUNCTION__), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            return redirect()->back(302)
                ->withInput($request->all())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        foreach ($request->get('questions') as $id => $q) {
            try {
                $question = Question::findOrFail($id);
            } catch (ModelNotFoundException $e) {
                throw_if(env('APP_DEBUG', false), $e);
                continue;
            }
            $review = new Review([
                'question_id' => $question->id,
                'sender_id' => Auth::user()->id,
                'title' => $question->title
            ]);
            switch (key($q)) {
                case 'linear-scale': // mark
                    $review->fill([
                        'recipient_id' => Course::DUMMY_ID,
                        'mark' => current($q)[0],
                        'type' => 's',
                    ]);
                    break;
                case 'multiple-choice': // answer
                    $review->fill([
                        'recipient_id' => Course::DUMMY_ID,
                        'answer' => $question->data['choices'][current($q)[0]],
                        'type' => 'c',
                    ]);
                    break;
                case 'paragraph': // comment
                    $review->fill([
                        'recipient_id' => Course::DUMMY_ID,
                        'comment' => current($q)[0],
                        'type' => 'p',
                    ]);
                    break;
                case 'criteria': // mark
                case 'eval': // mark
                    foreach ($q[key($q)] as $uid => $m) {
                        $r = new Review([
                            'question_id' => $question->id,
                            'title' => $question->title,
                            'sender_id' => Auth::user()->id,
                            'recipient_id' => $uid,
                            'type' => key($q) == 'criteria' ? 'r' : 'e',
                            'mark' => $m,
                        ]);
                        try {
                            $r->saveOrFail();
                        } catch (\Throwable $e) {
                            throw_if(env('APP_DEBUG', false), $e);
                            $request->session()->flash('message', array(
                                'level' => 'danger',
                                'heading' => 'Could not save review!',
                                'body' => sprintf("Please contact the system's administrator: %s", config('app.admin.address')),
                            ));
                            return redirect()
                                ->back(302)
                                ->withInput($request->all());
                        }
                    }
                    continue 2;
                default:
                    continue 2;
            }

            try {
                if ($review instanceof Review && $review->type)
                    $review->saveOrFail();
            } catch (\Throwable $e) {
                throw_if(env('APP_DEBUG', false), $e);
                $request->session()->flash('message', array(
                    'level' => 'danger',
                    'heading' => 'Could not save review!',
                    'body' => sprintf("Please contact the system's administrator: %s", config('app.admin.address')),
                ));
                return redirect()
                    ->back(302)
                    ->withInput($request->all());
            }
        }

        // @TODO: calculate mark
        $filled = new StudentSession(['user_id' => Auth::user()->id, 'session_id' => $session->id, 'mark' => 0]);
        $filled->save();
        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => 'You have successfully submitted the Form!',
        ]);
        return redirect()->action('SessionController@index')
            ->withHeaders($request->headers->all());
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return Response|RedirectResponse
     * @throws \Throwable
     */
    public function addGroup(Request $request, Session $session)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => 'Could not add a new Group!',
                'body' => $validator->messages()->first()
            ]);
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        $group = new Group(['name' => $request->get('title'), 'session_id' => $session->id]);
        try {
            $group->saveOrFail();
            $joined = new StudentGroup(['user_id' => Auth::user()->id, 'group_id' => $group->id]);
            $joined->saveOrFail();
        } catch (\Throwable $e) {
            throw_if(env('APP_DEBUG', false), $e);
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => 'Error!',
                'body' => "Could not create {$group->title}"
            ]);
        }
        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => 'Success!',
            'body' => "You have created & joined the Group {$group->title} successfully!" .
                " You can now fill the Session's Peer Assessment Form!"
        ]);
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return Response|RedirectResponse
     * @throws \Throwable
     */
    public function joinGroup(Request $request, Session $session)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => 'Could not join this Group!',
                'body' => $validator->messages()->first()
            ]);
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        try {
            $joined = new StudentGroup(['user_id' => Auth::user()->id, 'group_id' => $request->get('group_id')]);
            $joined->saveOrFail();

            $group = Group::whereId($request->get('group_id'))->firstOrFail();
        } catch (\Throwable $e) {
            throw_if(env('APP_DEBUG', false), $e);
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => "Error while joining Group!",
                'body' => "Something went wrong while joining Group!"
            ]);
            return redirect()->back()
                ->withInput($request->all());
        }

        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => 'Success!',
            'body' => "You have joined Group '{$group->title}' successfully!" .
                " You can now fill the Session's Peer Assessment Form!"
        ]);
        return redirect()->back();
    }
}
