<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
//            ->except([
//            'logout',
//            'verify', 'forgot', 'forgotSend', 'reset',
//            'create', 'store'
//        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $cid
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(int $cid, Request $request)
    {
        $user = Auth::user();
        $title = 'Session';

        try {
            $course = Course::findOrFail($cid);
        } catch (ModelNotFoundException $e) {
            throw abort(404);
        }
        $sessions = $course->sessions();

        return response(view('session.index', compact('title', 'course', 'sessions')), 200, $request->headers->all());
    }

}
