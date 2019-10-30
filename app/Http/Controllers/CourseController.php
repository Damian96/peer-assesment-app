<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
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
    }

    /**
     * @param string $action
     * @return array
     */
    private function messages(string $action)
    {
        $messages = [
            'title.required' => 'Course Title is required!',
            'title.min' => 'Course Title should be at least 5 characters.',
            'title.max' => 'Course Title should be at most 50 characters.',

            'code.required' => 'Course Code is required!',
            'code.min' => 'Course Code should be at least 6 characters.',
            'code.max' => 'Course Code should be at most 10 characters.',

            'instructor.required' => 'Instructor is required!',

            'description.max' => 'description is too long.',
        ];

        switch ($action) {
            case 'create':
            case 'store':
            case 'update':
                $messages = array_merge($messages, [
                    'instructor.required' => 'Instructor is required!',
                    'instructor.int' => 'Instructor MUST BE INT!',
                    'instructor.exists' => 'Instructor MUST A USER!',
                ]);
                break;
            case 'edit':
                break;
            default:
        }

        return $messages;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param String $action
     * @return array
     */
    private function rules(String $action)
    {
        $rules = [
            'title' => 'required|string|min:5|max:50',
            'code' => 'required|string|min:6|max:10',
            'description' => 'string|max:150'
        ];
        switch ($action) {
            case 'create':
            case 'store':
            case 'update':
                if (Auth::user()->isAdmin()) {
                    return array_merge($rules, [
                        'instructor' => 'required|int|exists:users,id',
                    ]);
                } else {
                    return $rules;
                }
            case 'edit':
                break;
            default:
        }
        return $rules;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'Courses';
        if (Auth::user()->isAdmin()) {
            $courses = Course::all()->all();
        } else {
            $courses = Auth::user()->courses()->getResults();
        }
        return response(view('course.index', compact('title', 'courses')), 200, $request->headers->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title = 'Create Course';
        return response(view('course.create', compact('title')), 200, $request->headers->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @method POST
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = 'Create';
        $validator = Validator::make($request->all(), $this->rules('create'), $this->messages('create'));
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->action('CourseController@create', [], 302)
                ->withInput($request->input())
                ->with('title', $title)
                ->with('errors', $validator->errors());
        }

        // TODO: try $request->all()
        $request->merge(['user_id' => intval($request->get('instructor'))]);
//        $request->merge(['updated_at' => Carbon::createFromTimestamp(time(), config('app.timezone'))->format(config('constants.date.stamp'))]);
        $course = new Course($request->all());
        if ($course->save()) {
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => 'Course created successfully!',
                'body' => ''
            ]);
            return redirect()->action('CourseController@show', $course->id, 302, $request->headers->all());
        }

        return redirect()->action('CourseController@create', [], 302)
            ->withInput($request->input())
            ->with('title', $title)
            ->with('errors', $validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function show(Request $request, int $id)
    {
        try {
            $course = Course::findOrFail($id)->refresh();
        } catch (ModelNotFoundException $e) {
            throw abort(404);
        }
        $title = $course->title;

        return response(view('course.show', compact('title', 'course')), 200, $request->headers->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return void
     */
    public function edit(Request $request, int $id)
    {
        $title = 'Edit Course';
        try {
            $course = Course::findOrFail($id)->refresh();
        } catch (ModelNotFoundException $e) {
            throw abort(404);
        }

        return response(view('course.edit', compact('title', 'course')), 200, $request->headers->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @method PUT
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, int $id)
    {
        $title = 'Edit Course';
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails()) {
            return redirect()->action('CourseController@edit', [$id], 302)
                ->withInput($request->input())
                ->with('title', $title)
                ->with('errors', $validator->errors());
        }

        try {
            $course = Course::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw abort(404);
        }

        $request->merge(['user_id' => intval($request->get('instructor'))]);
        $request->merge(['updated_at' => Carbon::createFromTimestamp(time(), config('app.timezone'))->format(config('constants.date.stamp'))]);
        if ($course->update($request->all())) {
            return redirect()->action('CourseController@show', [$course->id], 302, $request->headers->all());
        } else {
            throw abort(404);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
}
