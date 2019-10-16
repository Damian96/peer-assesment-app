<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @deprecated
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request
     * @return Response
     */
    public function home(Request $request) {
        if (strtolower($request->method()) !== 'get') {
            return abort('404');
        }

        $user = Auth::user();
        $title = 'Homepage';
        return response()
            ->view('user.home', compact('title', 'user'))
            ->withHeaders($request->headers->all());
    }

    /**
     * @param Request
     * @return Response
     */
    public function login(AdminRequest $request) {
        # Redirect to /home if already logged in
        if (Auth::guard('web')->check()) {
            $user = Auth::user();
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            return response()
                ->view('user.home',compact('title', 'user'))
                ->withHeaders($request->headers);
        }

        # If not logged in redirect to /login
        if (strtolower($request->method()) === 'get') {
            $title = 'Student Login';
            return response()
                ->view('user.login',compact('title'))
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
            $title = 'Home';
            return response()
                ->view('user.home',compact('title', 'user'))
                ->withHeaders($request->headers);
        } else {
            return redirect()->back()->withInput();
        }
    }
}
