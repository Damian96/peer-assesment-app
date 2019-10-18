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
use PhpParser\Node\Scalar\String_;

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
     * @param RegisterRequest $request
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
            return response()
                ->view('user.register', compact('title'))
                ->withHeaders($request->headers->all());
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
                'body' => 'Please check you email at ' .
                    '<a href="' . $user->email . '" target="_blank">' . $user->email . '</a>' .
                    'to complete the registration.'
            ];
            $request->session()->flash('message', $message);
            return redirect('/login', 302, $request->headers->all(), false);
        }

        return response()
            ->view('user.register', compact('title'))
            ->withHeaders($request->headers->all());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function home(Request $request) {
        if (strtolower($request->method()) !== 'get') {
            return abort('405');
        }

        $guardWeb = Auth::guard('web')->check();
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
     * @param Request $request
     * @return Response
     */
    public function login(Request $request) {
        # Redirect to /home if already logged in
        if (Auth::guard('web')->check()) {
            return redirect('/home', 302, $request->headers->all(), $request->secure());
        }

        if ($request->session()->has('_login')) {
            $title = 'Login';
            return response()
                ->view('user.login', compact('title'))
                ->withHeaders($request->headers->all());
        }

        $data = $request->session()->get('_login');
        $data = [
            'email' => isset($data['email']) ? $data['email'] : null,
            'password' => isset($data['password']) ? $data['password'] : null,
            'remember' => isset($data['remember']) ? $data['remember'] : null
        ];
        $validator = Validator::make($data, $this->rules('login'));
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->back()
                ->withInput()
                ->with('errors', $validator->getMessageBag());
        }

        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];
        if (Auth::attempt($credentials,
            isset($data['remember']) && $data['remember'] === '1')) {
            $user = User::getUserByEmail($data['email']);
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            Auth::setUser($user);
            $request->session()->forget('_login');
            return redirect('/home', 302, $request->headers->all(), false);
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
        return redirect('/', 302);
    }
}
