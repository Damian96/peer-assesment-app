<?php

namespace App\Http\Controllers;

use App\Course;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
                        'instructor' => [
                            'required',
                            'int',
                            Rule::exists('users', 'id')->where(function ($query) {
                                $query->where('instructor', '=', '1')
                                    ->orWhere('admin', '=', '1');
                            }),
                        ],
//                        'instructor' => 'required|int|exists:users,id',
//                        'instructor' => 'required|int|exists:users,id',
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
        $query = DB::table('courses');

        if (Auth::user()->isInstructor()) { # Instructor
            $query->where('user_id', '=', Auth::user()->id, 'and');
        } elseif (!Auth::user()->isAdmin()) { # Student
            $query->join('student_course', 'course_id', '=', 'id', 'inner', false);
            $query->where('student_course.user_id', '=', Auth::user()->id, 'and');
        } else { # Admin
            $query->leftJoin('users', 'users.id', '=', 'courses.user_id');
            $query->selectRaw("CONCAT(SUBSTR(fname, 1, 1), '. ', lname) AS instructor_name");
        }

        if ($request->get('ac_year', 0) > 0) {
            $ac_year = Carbon::create($request->get('ac_year'), 1, 1, 0, 0, 0, config('app.timezone'));
        } else {
            $ac_year = Carbon::now(config('app.timezone'));
        }
        $query->whereBetween('courses.ac_year', [$ac_year->startOfYear()->toDateTimeString(), $ac_year->endOfYear()->toDateTimeString()], 'and', false);
        $query->addSelect('courses.*');

        $query->orderBy('courses.created_at', 'desc');
        $courses = $query->paginate(Course::PER_PAGE, '*', 'page', $request->get('page', 1));
        $ac_year = $ac_year->year;
        return response(view('course.index', compact('title', 'courses', 'ac_year')), 200, $request->headers->all());
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
