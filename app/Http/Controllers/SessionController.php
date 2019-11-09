<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
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
                    'status' => 'nullable|boolean',
                    'instructions' => 'required|string|max:1000',
                    'deadline' => 'required|date_format:Y-m-d',
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
                    'status.required' => 'The Session status is required!',
                    'status.boolean' => 'The Session status must be boolean!',
                    'instructions.required' => 'The Session should have instructions',
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
     * @param Course $course
     * @return Response
     */
    public function create(Request $request, Course $course)
    {
        $title = 'Create Session - ' . $course->code;
        return \response(view('session.create', compact('title', 'course')), 200, $request->headers->all());
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
    }

}
