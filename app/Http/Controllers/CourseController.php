<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use Carbon\Carbon;
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
//        $this->middleware('verified');
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
            'department' => 'required|string|in:CCP,CES,CBE,CPY,MBA|max:255',
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
                        'ac_year' => 'sometimes|dateFormat:Y-m-d H:i:s|after:' . date('Y-m-d H:i:s', config('constants.date.start')),
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
        $query = DB::table('courses')
            ->leftJoin('users', 'users.id', '=', 'courses.user_id')
            ->addSelect(['courses.*', 'users.*']);

        $query->addSelect([
            'courses.user_id AS instructor_id',
            'courses.id AS course_id',
        ]);
        switch (Auth::user()->role()) {
            case 'admin':
                $query->selectRaw("CONCAT(SUBSTR(fname, 1, 1), '. ', lname) AS instructor_name");
                break;
            case 'instructor':
                $query->where('courses.user_id', '=', Auth::user()->id, 'and');
                break;
            case 'student':
                $query->leftJoin('student_course', 'course_id', '=', 'users.id');
                $query->addSelect(['student_course.user_id AS student_id']);
                $query->whereIn('courses.id', array_column(Auth::user()->courses()->get('course_id')->toArray(), 'course_id'));
                break;
            default:
                throw abort(404);
        }

        if (!Auth::user()->isStudent()) {
            if ($request->get('ac_year', false) == 'previous') {
                $ac_year = Course::toAcademicYear(strtotime('-1 year'));
                $query->where('ac_year', 'LIKE', $ac_year);
            } else {
                $ac_year = Course::toAcademicYear(time());
                $query->where('ac_year', 'LIKE', $ac_year);
            }
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
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
        $request->merge(['ac_year' => Course::getCurrentAcYear()]);
        $request->request->set('ac_year', Carbon::now(config('app.timezone'))->format(config('Y')));
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|void
     */
    public function edit(Request $request, Course $course)
    {
        $title = 'Edit Course ' . $course->code;
        $messages = $this->messages(__FUNCTION__);
        $departments = config('constants.departments.short');
        return response(view('course.edit', compact('title', 'course', 'messages', 'departments')), 200, $request->headers->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @method PUT
     * @param \Illuminate\Http\Request $request
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function update(Request $request, Course $course)
    {
        $title = 'Edit Course';

        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails()) {
            return redirect()->action('CourseController@edit', [$course->id], 302)
                ->withInput($request->input())
                ->with('title', $title)
                ->with('errors', $validator->errors());
        }

        if (Auth::user()->isAdmin()) { # administrator
            $request->merge(['user_id' => intval($request->get('instructor', $course->user_id))]);
        } elseif (Auth::user()->can('course.edit', ['id' => $course->id])) { # instructor-owner
            $request->merge(['user_id' => $course->user_id]);
        }
//        $request->merge(['ac_year' => Carbon::createFromDate(intval($request->get('ac_year', date('Y'))), 1, 1, config('app.timezone'))->format(config('constants.date.stamp'))]);

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

    /**
     * @method GET
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function students(Request $request, Course $course)
    {
        return \response(view('course.students', compact('title', 'students', 'course')));
    }
}
