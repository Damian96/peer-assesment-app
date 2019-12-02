<?php

namespace App\Http\Controllers;

use App\Course;
use App\Session;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
                return [
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
                return [
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
     * @param int $cid
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $cid)
    {
        $user = Auth::user();

        try {
            $course = Course::findOrFail($cid);
        } catch (ModelNotFoundException $e) {
            throw abort(404);
        }
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
        $title = 'Active Sessions';
        $sessions = Session::whereStatus('1')
            ->where('deadline', '>=', Carbon::now()->startOfDay()->format(config('constants.date.stamp')))
            ->orderBy('deadline', 'ASC')
            ->paginate(self::PER_PAGE);

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
        if ($course instanceof Course && $course->code) {
            $courses = null;
            $title = 'Create Session - ' . $course->code;
        } else {
            $course = null;
            $courses = Course::getCurrentYears();
            $title = 'Create Session';
        }
        $messages = $this->messages(__FUNCTION__);
        return \response(view('session.create', compact('title', 'course', 'messages', 'courses')), 200, $request->headers->all());
    }

    /**
     * @method POST
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            return redirect()->back(302)
                ->withInput($request->input())
                ->with('errors', $validator->errors());
        }

        $session = new Session($request->all());
        if ($session->save()) {
            if ($request->get('status', false) == '1') {
//                $session->
            }

        }

        if (env('APP_DEBUG', false)) {
            throw abort(500, 'Could not save Session!');
        } else {
            return redirect()->back(302);
        }
    }

}
