<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $this->middleware('student');
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
        $sessions = $course->sessions();

        return response(view('session.index', compact('title', 'course', 'sessions')), 200, $request->headers->all());
    }

    public function create(Request $request)
    {

    }

}
