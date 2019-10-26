<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
    }

    /**
     * @param string $string
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
                $messages = array_merge($messages, [
                    'instructor.required' => 'Instructor is required!',
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
            'user_id' => 'required|int',
            'code' => 'required|string|min:6|max:10',
            'description' => 'string|max:150'
        ];
        switch ($action) {
            case 'create':
                return array_merge($rules, [
                    'instructor' => 'required|int',
                ]);
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
        $courses = Auth::user()->courses()->getResults();
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
        return response(view('course.create', compact('title')),200, $request->headers->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = 'Create';
        $validator = Validator::make($request->all(), $this->rules('create'), $this->messages('create'));
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->back(302, $request->headers->all())
                ->withInput($request->input())
                ->with('errors', $validator->errors());
        }

        $attributes = [
            'title' => $request->get('title', null),
            'code' => $request->get('code', null),
            'user_id' => intval($request->get('instructor', 0))
        ];
        $course = new Course($attributes);
        if ($course->save()) {
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => 'Course created successfully!',
                'body' => ''
            ]);
            return redirect(sprintf("/view/%s", $course->id), 302, $request->headers->all(), $request->secure());
        }

        return redirect()->back(302, $request->headers->all())
            ->withInput($request->input())
            ->with('errors', $validator->errors());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param \App\Models\Course $course
     * @return void
     */
//    public function show(Course $course)
//    {
//        return response(view('course.show'))
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @param Request $request
     * @return void
     */
    public function edit(int $id, Request $request)
    {
        $title = 'Edit Course';
        try {
            $course = Course::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        return response(view('course.edit', compact('title', 'course')), 200, $request->headers->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return void
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Course  $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        //
    }
}
