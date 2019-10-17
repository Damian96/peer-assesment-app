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
    protected $redirectTo = '/home';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|max:50'
        ];
    }

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

        $validator = Validator::make($request->all(), $this->rules());
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
