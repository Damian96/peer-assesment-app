<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
            case 'register':
                return [
                    'email' => 'required|email|unique:users',
                    'password' => 'required|string|min:8|max:50',
                    'fname' => 'required|string|max:255',
                    'lname' => 'required|string|max:255',
                    'instructor' => 'string|min:1|max:3'
                ];
            case 'login':
                return [
                    'email' => 'required|email',
                    'password' => 'required|string|min:8|max:50',
                    'remember' => 'string|min:1|max:3'
                ];
            default:
                return [];
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function register(Request $request) {
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
        if (count($request->all()) == 0 || strtolower($request->method()) === 'get') {
            return view('user.register', compact('title'));
        }

        $validator = Validator::make($request->all(), $this->rules('register'));
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->back()
                ->withInput()
                ->with('errors', $validator->getMessageBag());
        }

        $attributes = [
            'fname' => $request->get('fname'),
            'lname' => $request->get('lname'),
            'email' => $request->get('email'),
            'department' => $request->get('department'),
            'reg_num' => $request->get('reg_num'),
            'password' => Hash::make($request->get('password')),
            'instructor' => '1'
        ];

        $user = new User($attributes);
        if ($user->save()) {
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            $message = [
                'heading' => 'You have successfully registered!',
                'body' => 'Please check you email at ' . $user->email . 'to complete the registration.'
            ];
            $request->session()->flash('message', $message);
            return redirect('/login', 302, $request->headers->all(), false);
        }

        return view('user.register', compact('title'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function home(Request $request) {
        if (strtolower($request->method()) !== 'get') {
            return abort('405');
        }

        if (Auth::guard('web')->check()) {
            $user = Auth::user();
            $title = 'Homepage';
            return view('user.home', compact('title', 'user'));
        } else {
            return redirect('/login', 302, $request->headers->all(), false);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request) {
        # Redirect to /home if already logged in
        if (Auth::guard('web')->check()) {
            return redirect('/home', 302, $request->headers->all(), $request->secure());
        }

        if ($request->method() === 'GET') {
            $title = 'Login';
            return view('user.login', compact('title'));
        }

        $attributes = [
            'email' => $request->get('email', null),
            'password' => $request->get('password', null),
            'remember' => $request->get('remember', null)
        ];
        $validator = Validator::make($attributes, $this->rules('login'));
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->back()
                ->withInput($request->all())
                ->with('errors', $validator->getMessageBag());
        }

        $credentials = [
            'email' => $attributes['email'],
            'password' => $attributes['password']
        ];
        if (Auth::attempt($credentials, boolval($attributes['remember']))) {
            $user = User::getUserByEmail($attributes['email']);
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            Auth::setUser($user);
            return redirect('/home', 302, $request->headers->all(), false);
        }

        return redirect()->back(302, $request->headers)->withInput($request->all());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request) {
        $request->session()->flush();
        Auth::logout();
        return redirect('/', 302);
    }
}
