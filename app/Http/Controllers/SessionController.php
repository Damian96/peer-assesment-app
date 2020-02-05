<?php

namespace App\Http\Controllers;

use App\Course;
use App\Form;
use App\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
//        $this->middleware('verified');
    }

    /**
     * @param string $action
     * @return array
     */
    public function rules(string $action)
    {
        switch ($action) {
            case 'create':
            case 'store':
            case 'edit':
            case 'update':
                return [
                    'form' => 'required|numeric|exists:forms,id',
                    'course' => 'required|numeric|exists:courses,id',
                    'title' => 'required|string|min:3|max:50',
                    'status' => 'nullable|boolean',
                    'instructions' => 'required|string|max:1000',
                    'deadline' => 'required|date_format:m-d-Y',
                ];
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
                    'status.required' => 'The Session status is required!',
                    'status.boolean' => 'The Session status must be boolean!',
                    'instructions.required' => 'The Session should have instructions!',
                    'instructions.max' => 'The Session instructions should not exceed 1000 characters!',
                    'deadline.required' => 'The Session deadline is required!',
                    'deadline.date_format' => 'The Session deadline should have a valid format!',
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
     * @TODO: change index to all and vice-versa
     */
    public function all(Request $request)
    {
        $user = Auth::user();
        $sessions = Session::all()->get();

        return response(view('session.all', compact('title', 'sessions')), 200, $request->headers->all());
    }

    /**
     * Display a listing of the resource, filtered by the specified Course.
     *
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\Response
     * @TODO: change index to all and vice-versa
     */
    public function index(Request $request, Course $course)
    {
        $title = $course->code . ' - Sessions';
        $sessions = $course->sessions()->getResults();

        return response(view('session.index', compact('title', 'course', 'sessions')), 200, $request->headers->all());
    }

    /**
     * @method GET
     * @param Request $request
     * @return Response
     */
    public function active(Request $request)
    {
        if (Auth::user()->isStudent()) {
            $title = 'Sessions';
        } else {
            $title = 'Active Sessions';
        }
        if (Auth::user()->isStudent()) {
            $courses = array_column(Auth::user()->studentCourses()->get(['id'])->toArray(), 'id');
            $sessions = Session::query()
                ->join('courses', 'courses.id', 'sessions.course_id')
                ->where('sessions.status', '=', '1')
                ->where('deadline', '>=', Carbon::now()->startOfDay()->format(config('constants.date.stamp')))
                ->whereIn('courses.id', $courses)
                ->orderBy('deadline', 'ASC')
                ->paginate(self::PER_PAGE);

        } else {
            $sessions = Auth::user()->sessions()
                ->where('sessions.status', '=', '1')
                ->where('deadline', '>=', Carbon::now()->startOfDay()->format(config('constants.date.stamp')))
                ->orderBy('deadline', 'ASC')
                ->paginate(self::PER_PAGE);
        }

        return response(view('session.active', compact('sessions', 'title')), 200, $request->headers->all());
    }

    /**
     * @method GET
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    public function create(Request $request, Course $course)
    {
        $forms = Auth::user()->forms();
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
        $forms = Form::all();
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
                ->with('errors', $validator->errors());
        }

        $request->request->add([
            'status' => $request->get('status', '1'),
            'course_id' => $request->get('course', false),
            'deadline' => Carbon::createFromTimestamp(strtotime($request->get('deadline', date('Y-m-d H:i:s'))))->format('Y-m-d H:i:s')
        ]);
        $session = new Session($request->all());
        if ($session->save()) {
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => sprintf("Session %s has been saved successfully!", $session->id),
            ]);
            if ($request->get('status', false) == '1') {
                $session->sendEmailNotification();
            } else {
                clock()->info('Session was disabled, so no Students have been notified!');
            }
            return redirect()->back(302, $request->headers->all());
        }

        if (env('APP_DEBUG', false)) {
            throw abort(500, sprintf("Could not save Session %s!", $session->id));
        } else {
            return redirect()->back(302, $request->headers->all());
        }
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
        $form = $session->form()->first();
        return response(view('session.fill', compact('title', 'form', 'session')), 200, $request->headers->all());
    }
}
