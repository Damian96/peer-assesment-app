<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    protected $redirectTo = '/home';

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
     * @param RegisterRequest $request
     * @return Response
     */
    public function register(RegisterRequest $request) {
        # Redirect to /home if already logged in
        if (Auth::guard('web')->check()) {
            $user = Auth::user();
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            return redirect('/home', 302, $request->headers->all(), false);
        }

        $title = 'Register';
        if (count($request->all()) == 0 && strtolower($request->method()) === 'get') {
            return response()
                ->view('user.register', compact('title'))
                ->withHeaders($request->headers->all());
        }

        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->back()->withInput();
        }

        $attributes = [
//            'name' => $request->get('name'),
            'fname' => $request->get('fname'),
            'lname' => $request->get('lname'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'instructor' => '1'
        ];

        $user = new User($attributes);
        if ($user->save()) {
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            $request->session()->flash('message',
                sprintf('%s<br>%s',
                    'You have successfully registered!',
                    'Please check you email at ' . $user->email . ' to complete the registration.')
            );
            return redirect('/login', 302, $request->headers->all(), false);
        }

        return response()
            ->view('user.register', compact('title'))
            ->withHeaders($request->headers->all());
    }

    /**
     * @param Request
     * @return Response
     */
    public function home(Request $request) {
        if (strtolower($request->method()) !== 'get') {
            return abort('405');
        }

        if (Auth::guard('web')->check()) {
            $user = Auth::user();
            $title = 'Homepage';
            return response()
                ->view('user.home', compact('title', 'user'))
                ->withHeaders($request->headers->all());
        } else {
            return redirect('/login', 302, $request->headers->all(), false);
        }
    }

    /**
     * @param Request
     * @return Response
     */
    public function login(Request $request) {
        # Redirect to /home if already logged in
        if (Auth::guard('web')->check()) {
            $user = Auth::user();
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            return redirect('/home', 302, $request->headers->all(), false);
        }

        if (count($request->all()) == 0 && strtolower($request->method()) === 'get') {
            $title = 'Login';
            return response()
                ->view('user.login', compact('title'))
                ->withHeaders($request->headers->all());
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
            return redirect('/home', 200, $request->headers->all(), false);
        }

        return redirect()->back()->withInput();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request) {
        $request->session()->flush();
        Auth::logout();
        return redirect('/', 200);
    }
}
