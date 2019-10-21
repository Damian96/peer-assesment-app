<?php


namespace App\Http\Controllers;


use App\Models\Course;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
     * Get the validation rules that apply to the request.
     *
     * @param String $action
     * @return array
     */
    public function rules(String $action)
    {
        switch ($action) {
            case 'create':
                return [
                    'title' => 'required|string|max:50',
                    'code' => 'required|string|min:6|max:10',
                    'instructor' => 'required|int',
                    'description' => 'string|max:150'
                ];
            default:
                return [];
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        $title = 'Create Course';

        if (count($request->all()) == 0 || strtolower($request->method()) === 'get') { # If not POST return plain view
            return response(view('course.create', compact('title')), 200, $request->headers->all());
        }

        $validator = Validator::make($request->all(), $this->rules('create'));
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->back()
                ->withInput()
                ->with('errors', $validator->getMessageBag());
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
            return response(view('course.create', compact('title')),200, $request->headers->all());
        }

        return response(view('course.create', compact('title')),200, $request->headers->all());
    }
}
