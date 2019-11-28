<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
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
        $this->middleware('role');
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

            'department.required' => 'A Department is required!',
            'department.in' => 'Invalid Department!',

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
            'department' => 'required|string|in:CS,ES,BS,PSY,MBA|max:255',
            'description' => 'nullable|string|max:150'
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
                        'ac_year' => 'sometimes|dateFormat:Y|after:' . config('constants.date.start'),
                    ]);
                } elseif (Auth::user()->isInstructor()) {
                    return $rules;
                } else break;
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
//            $query->whereIntegerInRaw('user_id', [Auth::user()->id]);
        } elseif (!Auth::user()->isAdmin()) { # Student
            $query->join('student_course', 'course_id', '=', 'id', 'inner', false);
            $query->where('student_course.user_id', '=', Auth::user()->id, 'and');
        } else { # Admin
            $query->leftJoin('users', 'users.id', '=', 'courses.user_id');
            $query->selectRaw("CONCAT(SUBSTR(fname, 1, 1), '. ', lname) AS instructor_name");
        }

        if (!Auth::user()->isStudent()) {
            if (intval(date('m')) >= 8) {
                $ac_year = Carbon::now(config('app.timezone'))->setMonth(9)->startOfMonth();
            } else {
                $ac_year = Carbon::now(config('app.timezone'))->setMonth(4)->startOfMonth();
            }
            if ($request->get('ac_year', false) == 'previous') {
                $query->where('ac_year', '<=', $ac_year->toDateString());
            } else {
                $query->where('ac_year', '>=', $ac_year->toDateString());
            }
//            return dd($query->toSql(), $ac_year->toDateString());
        } else {
            $ac_year = intval(date('Y'));
        }

        $query->orderBy('courses.created_at', 'desc');
        $courses = $query->paginate(Course::PER_PAGE, '*', 'page', $request->get('page', 1));
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
        $messages = $this->messages(__FUNCTION__);
        return response(view('course.create', compact('title', 'messages')), 200, $request->headers->all());
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

        $request->merge(['user_id' => intval($request->get('instructor', Auth::user()->id))]);
        $request->merge(['ac_year' => Carbon::createFromDate(intval($request->get('ac_year', date('Y'))), 1, 1, config('app.timezone'))->format(config('constants.date.stamp'))]);
        $course = new Course($request->all());
        if ($course->save()) {
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => 'Course created successfully!',
                'body' => ''
            ]);
            return redirect()->action('CourseController@show', [$course->id], 302, $request->headers->all());
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
     * @param Course $course
     * @return void
     */
    public function show(Request $request, Course $course)
    {
        $title = $course->code . ' - ' . $course->ac_year_pair;

        $similars = Course::whereCode($course->code)
            ->whereNotIn('id', [$course->id])
            ->getModels();

        $students = User::whereInstructor('0')
            ->where('admin', '=', '0')
            ->whereNotNull('email_verified_at')
            ->getModels();

        return response(view('course.show', compact('title', 'course', 'similars', 'students')), 200, $request->headers->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param Course $course
     * @return void
     */
    public function edit(Request $request, Course $course)
    {
        $title = 'Edit Course ' . $course->code;
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

        try {
            $course = Course::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw abort(404);
        }

//        if ($request->has('copy')) {
//            $clone = $course->copyToCurrentYear();
//            return redirect()->action('CourseController@show', [$clone], 302)
//                ->withInput($request->input())
//                ->with('title', $title);
//        }

        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails()) {
            return redirect()->action('CourseController@edit', [$id], 302)
                ->withInput($request->input())
                ->with('title', $title)
                ->with('errors', $validator->errors());
        }

        if (Auth::user()->isAdmin()) { # administrator
            $request->merge(['user_id' => intval($request->get('instructor', $course->user_id))]);
        } elseif (Auth::user()->can('course.edit', ['id' => $id])) { # instructor-owner
            $request->merge(['user_id' => $course->user_id]);
        }
        $request->merge(['ac_year' => Carbon::createFromDate(intval($request->get('ac_year', date('Y'))), 1, 1, config('app.timezone'))->format(config('constants.date.stamp'))]);

        if ($course->fill($request->all())->save()) {
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => 'Course Updated successfully!',
            ]);
            return redirect()->action('CourseController@show', [$course->id], 302, $request->headers->all());
        } else {
            throw abort(404);
        }

    }

    /**
     * Copy the specified Course to the current academic year.
     * @method POST
     * @param Request $request
     * @return void
     */
    public function copy(Request $request, Course $course)
    {
        $clone = $course->copyToCurrentYear();
        $title = 'Course ' . $course->code;
        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => 'The Course has been successfully copied!',
        ]);
        return redirect()->action('CourseController@show', [$clone], 302)
            ->withInput($request->input())
            ->with('title', $title);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @method DELETE
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Course $course)
    {
        try {
            if ($course->delete()) {
                $request->session()->flash('message', [
                    'level' => 'success',
                    'heading' => sprintf("Course %s, was deleted successfully!", $course->code),
                    'body' => '',
                ]);
                return redirect()->action('CourseController@index', [], 302);
            } elseif (env('APP_DEBUG', false)) {
                $request->session()->flash('message', [
                    'level' => 'danger',
                    'heading' => sprintf("Something went wrong while deleting %s!", $course->code),
                    'body' => 'Please contact the administrator, at : ' . config('app.admin.address'),
                ]);
                return redirect()->back(302);
            }
        } catch (\Exception $e) {
            if (env('APP_DEBUG', false)) {
                throw abort(500, $e->getMessage());
            } else {
                return redirect()->back(302);
            }
        }
    }
}
