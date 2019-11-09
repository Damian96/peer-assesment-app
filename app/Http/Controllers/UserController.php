<?php

namespace App\Http\Controllers;

use App\Course;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Class UserController
 * @package App\Http\Controllers
 *
 * TODO: implement ErrorController
 */
class UserController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @deprecated
     * @var string
     */
    protected $redirectTo = '/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('guest')->except([
            'logout', 'auth', # login-logout
//            'create', 'store', # user-register
            'verify', # verify-email/password
            'forgot', 'forgotSend', 'reset', 'update', # reset-password
        ]);
        $this->middleware('role')->except([
            'logout', 'login', 'auth', # login-logout
            'create', 'store', # user-register
            'addStudent', 'storeStudent',
//            'verify', # verify-email/password
            'forgot', 'forgotSend', 'reset', 'update', # reset-password
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param String $action
     * @param array $options
     * @return array
     *
     * TODO: add strong password regex rule
     */
    public function rules(String $action, array $options = [])
    {
        switch ($action) {
            case 'reset':
                return [
                    '_method' => 'required|string|in:PUT',
                    'email' => 'required|email|regex:/^.+@citycollege\.sheffield\.eu$/im|exists:users',
                    'token' => 'required|string|min:60|max:60|exists:password_resets',
                    'password' => 'required|confirmed|min:3|max:50',
                ];
            case 'forgot':
            case 'forgotSend':
                return [
                    'email' => 'required|email|regex:/^.+@citycollege\.sheffield\.eu$/im|exists:users',
                ];
            case 'storeStudent':
            case 'register.student':
                return [
                    'fname' => 'required|string|min:3|max:25',
                    'lname' => 'required|string|min:3|max:25',
                    'department' => 'required|string|max:5|different:admin',
                    'reg_num' => 'required|string|regex:/^@[A-Z]{2}[0-9]{4}$/im|max:6',
                    'email' => 'required|email|regex:/^.+@citycollege\.sheffield\.eu$/im|unique:users,email',
                ];
            case 'register.user':
            case 'register':
            case 'create':
            case 'store':
                return [
                    'email' => 'required|email:filter|regex:/^[a-z]+@citycollege\.sheffield\.eu$/|unique:users',
                    'password' => 'required|string|min:3|max:50',
                    'fname' => 'required|string|min:3|max:25',
                    'lname' => 'required|string|min:3|max:25',
                    'instructor' => 'nullable|boolean',
                    'terms' => 'accepted',
                    'g-recaptcha-response' => env('APP_ENV', false) == 'local' || env('APP_DEBUG', false) ? 'required_without:localhost|sometimes|string|recaptcha' : 'required|string|recaptcha'
                ];
            case 'auth':
            case 'login':
                return [
                    'email' => 'required|email:filter|regex:/^[a-z]+@citycollege\.sheffield\.eu$/|exists:users',
                    'password' => 'required|string|min:3|max:50',
                    'remember' => 'nullable|accepted',
                    'g-recaptcha-response' => env('APP_ENV', false) == 'local' || env('APP_DEBUG', false) ? 'required_without:localhost|sometimes|string|recaptcha' : 'required|string|recaptcha'
                ];
            default:
                return [];
        }
    }

    /**
     * @param string $action
     * @return array
     */
    private function messages(string $action)
    {
        $messages = [
            'email.required' => 'We need to know your e-mail address!',
            'email.regex' => 'The e-mail must be an academic one!',
            'email.filter' => 'Invalid e-mail address!',
            'email.email' => 'Invalid e-mail address!',
            'email.unique' => 'Invalid e-mail address!',

            'password.required' => 'Your password is required!',
            'password.min' => 'Your password should be at least 3 characters!',
            'password.max' => 'Password is too long.',
        ];

        switch ($action) {
            case 'forgot':
            case 'forgotSend':
                return [
                    'email.required' => 'We need to know your e-mail address!',
                    'email.regex' => sprintf('%s', 'The e-mail must be an academic one!'),
                    'email.exists' => 'Invalid e-mail address.',
                ];
            case 'auth':
            case 'login':
                return array_merge($messages, [
                    'email.exists' => 'Invalid e-mail address',

                    'g-recaptcha-response.required' => 'Please fill the re-captcha',
                    'g-recaptcha-response.string' => 'Invalid re-captcha',
                    'g-recaptcha-response.recaptcha' => 'Invalid re-captcha',
                ]);
            case 'register.student':
            case 'storeStudent':
                return [
                    'email.required' => 'We need to know your e-mail address!',
                    'email.regex' => 'The e-mail must be an academic one!',
                    'email.email' => 'Invalid e-mail address!',
                    'email.filter' => 'Invalid e-mail address!',
                    'email.unique' => 'Invalid e-mail address!',

                    'department.required' => 'The student should be assigned to a Department!',
                    'department.different' => 'The student should be assigned to a Department!',
                    'department.max' => 'Invalid Department.',
                    'department.string' => 'Invalid Department.',

                    'reg_num.required' => 'The student should have a Registration number!',
                    'reg_num.string' => 'Invalid Registration number.',
                    'reg_num.regex' => 'Invalid Registration number format! Correct format is: "AB12013"',
                    'reg_num.max' => 'Invalid Registration number.',

                    'fname.required' => 'The student should have a first name!',
                    'fname.min' => 'First name should be at least 3 characters!',
                    'fname.max' => 'First name should be at most 25 characters!',

                    'lname.required' => 'The student should have a last name!',
                    'lname.min' => 'First name should be at least 3 characters!',
                    'lname.max' => 'First name should be at most 25 characters!',
                ];
            case 'register.user':
            case 'store':
            case 'create':
                $messages = array_merge($messages, [
                    'fname.required' => 'We need to know your first name!',
                    'lname.required' => 'We need to know your last name!',

                    'terms.required' => 'You must accept our Terms and Conditions.'
                ]);
                break;
        }

        return $messages;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $title = 'Homepage';

//        if (!$user->hasVerifiedEmail()) {
//            $request->session()->flash('emailVerifiedSent', true);
//            $user->sendEmailVerificationNotification();
//            $request->session()->flash('message', [
//                'level' => 'info',
//                'heading' => 'You need to verify your email',
//                'body' => 'We\'ve sent a link to ' . $user->getEmailForVerification() . '.' .
//                    'Follow the instructions there to complete your registration.'
//            ]);
//        }

        return response(view('user.home', compact('title', 'user')), 200, $request->headers->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (Auth::guard('web')->check()) { # Redirect to /home if already logged in
            $user = Auth::user();
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            return redirect('/home', 302, $request->headers->all(), $request->secure());
        }

        $title = 'Register';
        $messages = $this->messages('register');
        return response(view('user.register', compact('title', 'messages')), 200, $request->headers->all());
    }

    /**
     * @method GET
     * @param Request $request
     * @param Course $course
     * @return Response
     */
    public function addStudent(Request $request, Course $course)
    {
        $title = 'Add Student';
        $messages = $this->messages('register.student');
        return response(view('user.addStudent', [
            'title' => $title,
            'messages' => $messages,
            'user' => Auth::user(),
            'course' => $course,
        ]), 200, $request->headers->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @method POST
     * @param \Illuminate\Http\Request $request
     * @param Course $course
     * @return void
     */
    public function storeStudent(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails()) {
            return redirect()->action('UserController@create', 302)
                ->withInput($request->input())
                ->with('title', 'Register')
                ->with('errors', $validator->getMessageBag());
        }

        try {
            $password = random_int(1000, 2000) .
                substr($request->get('fname'), 0, 3) .
                substr($request->get('reg_num'), -3);
        } catch (\Exception $e) {
            throw abort(500);
        }
        $request->merge(['password' => Hash::make($password)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @method POST
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails()) {
            return redirect()->action('UserController@create', 302)
                ->withInput($request->input())
                ->with('title', 'Register')
                ->with('errors', $validator->getMessageBag());
        }

        $request->merge(['password' => Hash::make($request->get('password'))]);
        $user = new User($request->all());
        if ($user->save()) {
            $user->sendEmailVerificationNotification();
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => 'You have successfully registered!',
                'body' => 'Please check you email at ' . $user->email . ' to complete the registration.'
            ]);
            return redirect('/login', 302, $request->headers->all(), $request->secure());
        }

        return redirect()->back(302, $request->headers->all())
            ->withInput($request->input())
            ->with('title', 'Register')
            ->with('messages', $this->messages('register'))
            ->with('errors', $validator->getMessageBag());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        return response(view('user.login', ['title' => 'Login']), 200, $request->headers->all());
    }

    /**
     * @method POST
     * @param Request $request
     * @return Response
     */
    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails()) {
            return redirect()->back(302, $request->headers->all(), $request->secure())
                ->withInput($request->input())
                ->with('errors', $validator->errors()->getMessageBag());
        }

        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')], boolval($request->get('remember', false)))) {
            $user = User::getUserByEmail($request->get('email'));
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            Auth::setUser($user);
            if ($user->isStudent()) {
                return redirect('courses', 302, $request->headers->all(), $request->secure());
            } else {
                return redirect($this->redirectTo, 302, $request->headers->all(), $request->secure());
            }
        }

        throw abort(500, 'Could not authenticate user', $request->headers->all());
    }

    /**
     * Display the specified user's profile.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        $title = $user->fullname . ' Profile';
        return response(view('user.profile', compact('title', 'user')), 200, $request->headers->all());
    }

    /**
     * Show the authenticated user's profile
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $title = 'Profile';
        $user = Auth::user();
        return response(view('user.profile', compact('title', 'user')), 200, $request->headers->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
//    public function edit(User $user)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|Response
     */
    public function update(Request $request)
    {
        if (!$request->has('action')) {
            throw abort(404);
        }

        if (strtolower($request->get('action')) == 'reset' && $request->has(['email', 'token'])) { # reset-password
            $validator = Validator::make($request->all(), $this->rules('reset'), $this->messages('reset'));
            try {
                $user = User::where('email', $request->get('email'))->firstOrFail();
            } catch (ModelNotFoundException $e) {
                throw abort(404);
            }

            if ($validator->fails()) {
                $title = 'Reset Password';
                $token = $request->get('token');
                $email = $request->get('email');
                $errors = $validator->errors();
                return \response(view('user.reset', compact('title', 'token', 'email', 'errors')), 200, $request->headers->all());
            }

            $user->fill(['password' => Hash::make($request->get('password'))])->save();
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => 'Password successfully changed!',
            ]);
            // TODO: add password copy email,
            // TODO: remove password_resets row
            return redirect('/login', 302, $request->headers->all(), $request->secure());
        } else {
            throw abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $user
     * @return \Illuminate\Http\Response
     */
//    public function destroy(User $user)
//    {
//        //
//    }

    /**
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return redirect('/', 302, $request->headers->all());
    }

    /**
     * Verify an email link, and redirect to /reset or verify user's email
     * @param Request $request
     * @return Response
     */
    public function verify(Request $request)
    {
        if (!$request->has(['id', 'hash', 'expires', 'action']) || !in_array($request->get('action', false), ['email', 'password'], true)) {
            throw abort(401);
        }

        $redirect_fail = Auth::check() ? 'UserController@index' : 'UserController@login';
        $request->query = new ParameterBag();

        try {
            $user = User::whereId(intval($request->get('id', -1)))->firstOrFail()->refresh();
        } catch (ModelNotFoundException $e) { # invalid user / hacking attempt;
            throw abort(401);
        }

        if (strcmp($request->get('hash', null), sha1($user->getEmailForVerification())) != 0) { # invalid user / hacking attempt;
            throw abort(401);
        }

        if (Carbon::now(config('app.timezone'))->timestamp > intval($request->get('expires', 0))) { # link expired;
            $request->session()->flash('message', [
                'level' => 'warning',
                'heading' => 'We are sorry.',
                'body' => $request->get('action', false) == 'email' ?
                    'The verification link has expired. Please login to your account and try again.' :
                    'The link has expired. Please try again.'
            ]);
            return redirect()->action($redirect_fail, [], 302, $request->headers->all());
        }

        if ($request->get('action', false) == 'email') {
            $user->markEmailAsVerified();
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => 'E-mail Verification successful!',
            ]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            return redirect()->action('UserController@index', [], 302, $request->headers->all());
        } elseif ($request->get('action', false) == 'password') {
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            $request->session()->flash('user', $user);
            return redirect()->action('UserController@reset', [], 302, $request->headers->all());
        } else { # unknown action / hacking attempt
            throw abort(401);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function reset(Request $request)
    {
        $title = 'Reset Password';

        if (!$request->session()->has('user')) {
            throw abort(404);
        }
        $user = $request->session()->get('user');
        if (!$user instanceof \App\User) {
            throw abort(404);
        }
        $email = $user->email;

        $token = DB::table('password_resets')
            ->where('email', $email)
            ->get('token')->first();
        if (!property_exists($token, 'token')) {
            throw abort(404);
        } else {
            $token = $token->token;
        }

        return \response(view('user.reset', compact('title', 'user', 'email', 'token')), 200, $request->headers->all());
    }

    /**
     * @param Request $request
     * @method GET
     * @return Response
     */
    public function forgot(Request $request)
    {
        $title = 'Forgot Password';
        $messages = $this->messages(__FUNCTION__);
        return \response(view('user.forgot', compact('title', 'messages')), 200, $request->headers->all());
    }

    /**
     * @param Request $request
     * @method POST
     * @return Response
     */
    public function forgotSend(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            return redirect()->action('UserController@forgot', [], 302)
                ->withInput($request->all())
                ->with('messages', $this->messages(__FUNCTION__))
                ->with('errors', $validator->errors());
        }

        $user = User::getUserByEmail($request->get('email'));
        if (!$user->hasVerifiedEmail()) {
            $request->session()->flash('message', [
                'level' => 'warning',
                'heading' => 'You need to verify your e-mail!',
                'body' => sprintf("We can not send you the reset e-mail, until you verify your email. If you still can not remember your password, please contact us at <a href='mailto:%s' title='Administrator'>%s</a>", config('app.admin.address'), config('app.admin.address'))
            ]);
            return redirect()->action('UserController@login', [], 302);
        }

        $user->sendPasswordResetNotification($user->getPasswordResetToken());
        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => 'Reset Email Sent Successfully!',
            'body' => 'Check you inbox for our email, and follow the instructions to reset your password.'
        ]);
        return redirect()->action('UserController@login', [], 302);
    }
}
