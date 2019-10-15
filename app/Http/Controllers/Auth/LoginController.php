<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('web');
        $this->middleware('guest')->except('logout');

        $this->redirectTo = Config::get('options.student.home');
    }

    /**
     * @param Request
     * @return Response
     */
    public function login(AdminRequest $request) {
        if (Auth::guard('web')->check()) {
            $user = Auth::user();
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            return redirect()->to(Config::get('constants.options.student.home'), 301, $request->headers->all(), $request->secure());
        }

        if (strtolower($request->method()) === 'get') {
            $title = 'Student Login';
            return response()
                ->view("user.login",compact('title'))
                ->withHeaders($request->headers);
        }

        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->back()->withInput();
        }

        $credentials = [ 'email' => $request->get('email'), 'password' => $request->get('password') ];

        if (Auth::attempt($credentials)) {
            $user = User::getUserByEmail($request->get('email'));
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            Auth::setUser($user);
            return redirect()->to(Config::get('constants.options.student.home'), 301, $request->headers->all(), $request->secure());
        } else {
            return redirect()->back()->withInput();
        }
    }
}
