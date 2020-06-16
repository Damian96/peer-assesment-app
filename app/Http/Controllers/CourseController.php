<?php

namespace App\Http\Controllers;

use App\Course;
use App\StudentCourse;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\RedirectResponse;
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
        $this->middleware('verified');
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
            case 'search':
                return [
                    'keyword.required' => 'The keyword is required!',
                    'keyword.min' => 'The keyword should be at least 5 characters long!',
                    'keyword.max' => 'The keyword should be at most 50 characters long!',
                    'keyword.regex' => 'The keyword is not valid!',
                ];
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
    private function rules(string $action)
    {
        $rules = [
            'title' => 'required|string|min:5|max:50',
            'code' => 'required|string|min:6|max:10',
            'department' => 'required|string|in:CCP,CES,CBE,CPY,MBA|max:255',
            'description' => 'nullable|string|max:150'
        ];
        switch ($action) {
            case 'disenroll':
                return [
                    '_method' => 'required|in:DELETE',
                    'user_id' => 'required|integer|exists:users,id'
                ];
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
            case 'search':
                return [
                    'method' => '_GET',
                    'keyword' => 'required|string|min:5|max:50|regex:/^[A-Za-z\d\s]*$/g'
                ];
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
                $query->where('courses.user_id', '=', Auth::user()->id);
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
            $ac_year = Course::toAcademicYear(time());
            if ($request->get('ac_year', false) == 'previous') {
                $query->where('ac_year', 'NOT LIKE', $ac_year);
            } else {
                $query->where('ac_year', 'LIKE', $ac_year);
            }
        } else {
            $ac_year = intval(date('Y'));
        }

        // Custom Pagination
        $query->where('courses.id', '!=', Course::DUMMY_ID)
            ->orderBy('courses.created_at', 'desc');
        $page = intval($request->get('page', 1));
        if ($query->count() <= Course::PER_PAGE * $page)
            $courses = $query->paginate(Course::PER_PAGE, '*', 'page', $page);
        else {
            while ($query->count() < Course::PER_PAGE * $page)
                $page--;
            $courses = $query->paginate(Course::PER_PAGE, '*', 'page', $page);
        }

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
        $request->request->set('ac_year', Course::toAcademicYear(now(config('app.timezone'))->timestamp));
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
        abort_if($course->id == Course::DUMMY_ID, 404);

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

        $course = $course->fill($request->all());
        $course->ac_year_carbon = null;
        if ($course->save()) {
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
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function copy(Request $request, Course $course)
    {
        try {
            $clone = $course->copyToCurrentYear();
        } catch (\Throwable $e) {
            throw_if(config('env.APP_DEBUG', false), $e);
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => 'Something went wrong while coping the Course!',
            ]);
            return redirect()->back();
        }
        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => 'The Course has been successfully copied!',
        ]);
        return redirect()->action('CourseController@show', ['course' => $clone]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @method DELETE
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Course $course)
    {
        if ($course->sessions()->exists()) {
            $request->session()->flash('message', [
                'level' => 'warning',
                'heading' => 'Cannot delete Course!',
                'body' => 'This course is associated another Session(s), and cannot be deleted!'
                    . '<br>' . 'If you truly want to delete it, please delete its associated Session(s) first.'
            ]);
            return redirect()->back(302);
        }

        try {
            if ($course->delete()) {
                $request->session()->flash('message', [
                    'level' => 'success',
                    'heading' => sprintf("Course %s, was deleted successfully!", $course->code),
                    'body' => '',
                ]);
                return redirect()->action('CourseController@index', [], 302);
            } elseif (config('env.APP_DEBUG', false)) {
                $request->session()->flash('message', [
                    'level' => 'danger',
                    'heading' => sprintf("Something went wrong while deleting %s!", $course->code),
                    'body' => 'Please contact the administrator, at : ' . config('app.admin.address'),
                ]);
                return redirect()->back(302);
            }
        } catch (\Exception $e) {
            if (config('env.APP_DEBUG', false)) {
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

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Throwable
     */
    public function disenroll(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            $request->session()->flash('message', [
                'level' => 'warning',
                'heading' => 'Could not disenroll Student from Course!',
                'body' => $validator->errors()->first()
            ]);
            return redirect()->back()
                ->withInput($request->all());
        }

        try {
            $student = User::whereId($request->get('user_id', 0))
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('message', [
                'level' => 'warning',
                'heading' => 'Could not disenroll Student from Course!',
                'body' => $validator->errors()->first()
            ]);
            return redirect()->back();
        }

        if (!$student->isRegistered($course->id)) {
            $request->session()->flash('message', [
                'level' => 'warning',
                'heading' => "Something went wrong.",
                'body' => "The Student {$student->lname} is not enrolled in this course!"
            ]);
            return redirect()->back();
        }

        $enrolled = StudentCourse::whereCourseId($course->id)
            ->where('user_id', '=', $student->id)
            ->first();

        try {
            $enrolled->delete();
        } catch (\Exception $e) {
            throw_if(config('env.APP_DEBUG', false), $e);
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => "Something went wrong.",
                'body' => "Could not disenroll Student {$student->lname} from Course!"
            ]);
            return redirect()->back();
        }

        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => "Disenroll Successful!",
            'body' => "Student {$student->lname} has been disenrolled from Course!"
        ]);
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Iluminate\Http\Response|Illuminate|Http|RedirectResponse
     */
//    public function search(Request $request)
//    {
//        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
//        if ($validator->fails()) {
//            $request->session()->flash('message', [
//                'heading' => 'Your search is not correct!',
//                'level' => 'danger',
//                'body' => $validator->errors()->getMessageBag()->first()
//            ]);
//            return redirect()->back()
//                ->withInput($request->all())
//                ->withErrors($validator->errors()->getMessageBag());
//        }
//
//        return redirect()->back();
//    }
}
