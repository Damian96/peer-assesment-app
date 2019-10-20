<?php


namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
                    'user_id' => 'required|int',
                    'code' => 'required|string|min:6|max:10',
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
        return response(view('course.create', ['title' => $title]),200, $request->headers->all());
    }
}
