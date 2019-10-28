<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\Rule;
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
        $this->middleware('guest')->except(['logout', 'create', 'store', 'verify', 'change', 'reset']);
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
            case 'register':
                return [
                    'email' => 'required|email|regex:/^.+@citycollege\.sheffield\.eu$/im|unique:users',
                    'password' => 'required|string|min:8|max:50',
                    'fname' => 'required|string|min:3|max:255',
                    'lname' => 'required|string|min:3|max:255',
                    'instructor' => 'boolean',
                    'terms' => 'boolean',
                ];
            case 'login':
                return [
                    'email' => 'required|email|regex:/^.+@citycollege\.sheffield\.eu$/im|exists:users',
                    'password' => 'required|string|min:8|max:50',
                    'remember' => 'nullable|boolean',
                    'localhost' => 'nullable|string|max:1',
                    'g-recaptcha-response' => 'required_unless:localhost,1|string|recaptcha'
                ];
            case 'change.2':
                return [
                    'email' => 'required|email|regex:/^.+@citycollege\.sheffield\.eu$/im|exists:users',
                    'g-recaptcha-response' => 'required_unless:localhost,1|string|recaptcha'
                ];
            case 'change.3':
                $rules = [ 'code' => [
                    'required',
                    'max' => 6,
                ]];
                if (isset($options['email'])) {
                    array_push($rules['code'], Rule::exists('staff')->where(function ($query) use($options) {
                        $query->where('email', $options['email']);
                    }));
                }
                return $rules;
            case 'change.4':
                return [
                    'password' => 'required|string|min:8|max:50|confirmed'
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
            'email.regex' => sprintf('%s', 'The email must be an academic one!'),

            'password.required' => 'Your password is required!',
            'password.min' => 'Your password should be at least 8 characters!',
            'password.max' => 'Password is too long.',
        ];

        switch ($action) {
            case 'login':
                return array_merge($messages, [
                    'email.exists' => 'Invalid e-mail address',

                    'g-recaptcha-response.required' => 'Please fill the re-captcha',
                    'g-recaptcha-response.string' => 'Invalid re-captcha',
                    'g-recaptcha-response.recaptcha' => 'Invalid re-captcha',
                ]);
            case 'register':
                $messages = array_merge($messages, [
                    'fname.required' => 'We need to know your first name!',
                    'lname.required' => 'We need to know your last name!',

                    'terms.required' => 'You must accept our Terms and Conditions.'
                ]);
                break;
            case 'change.2':
                $messages = [
                    'email.required' => 'We need to know your e-mail address!',
                    'email.regex' => sprintf('%s', 'The email must be an academic one!'),
                    'email.unique' => 'Invalid email address',

                    'g-recaptcha-response.required' => 'Please fill the re-captcha',
                    'g-recaptcha-response.string' => 'Invalid re-captcha',
                    'g-recaptcha-response.recaptcha' => 'Invalid re-captcha',
                ];
                break;
            case 'change.3':
                $messages = [
                    'code.required' => 'The verification code is required!',
                    'code.max' => 'Invalid verification code',
                    'code.exists' => 'Invalid verification code',
                ];
                break;
            case 'change.4':
                return array_merge($messages, [
                    'password_confirmation' => 'You need to confirm your new password!'
                ]);
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

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            $request->session()->flash('message', [
                'level' => 'info',
                'heading' => 'You need to verify your email',
                'body' => 'We\'ve sent a link to ' . $user->getEmailForVerification() . '.' .
                    'Follow the instructions there to complete your registration.'
            ]);
        }

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
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            return redirect('/home', 302, $request->headers->all(), false);
        }

        $title = 'Register';
        $messages = $this->messages('register');
        return response(view('user.register', compact('title','messages')), 200, $request->headers->all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules('register'), $this->messages('register'));
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->back(302, $request->headers->all())
                ->withInput($request->input())
                ->with('title', 'Register')
                ->with('errors', $validator->getMessageBag());
        }

        $attributes = [
            'fname' => $request->get('fname'),
            'lname' => $request->get('lname'),
            'email' => $request->get('email'),
            'department' => $request->get('department'),
            'reg_num' => $request->get('reg_num'),
            'password' => Hash::make($request->get('password')),
            'instructor' => $request->get('instructor', 0)
        ];
        $user = new User($attributes);
        if ($user->save()) {
            $user->sendEmailVerificationNotification();
            $request->merge(['user' => $user]);
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
    public function login(Request $request) {
        if (strtolower($request->method()) == 'get') {
            $title = 'Login';
            return response(view('user.login', compact('title')), 200, $request->headers->all());
        }

        $validator = Validator::make($request->all(), $this->rules('login'), $this->messages('login'));
        if ($validator->fails()) {
            $request->session()->flash('error', $validator->getMessageBag()->first());
            return redirect()->back(302, $request->headers->all())
                ->withInput($request->all())
                ->with('errors', $validator->getMessageBag());
        }

        $credentials = [
            'email' => $request->get('email'),
            'password' => $request->get('password')
        ];
        if (Auth::attempt($credentials, boolval($request->get('remember')))) {
            $user = User::getUserByEmail($request->get('email'));
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            Auth::setUser($user);
            return redirect('/home', 302, $request->headers->all(), false);
        }

        return redirect()->back(302, $request->headers->all())
            ->withInput($request->all())
            ->with('errors', $validator->getMessageBag());
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $title = 'Profile';
        $user = Auth::user();
        return response(view('user.profile', compact('title', 'user')), 200, $request->headers->all());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
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
     * @param int $id
     * @return void //
     */
    public function update(Request $request, int $id)
    {
        if (!$request->has('action')) {
            throw abort(404);
        }

        try {
            $user = User::whereId($id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw abort(404);
        }

        if ($request->get('action') == 'reset' && $request->has('password')) {
            $validator = Validator::make($request->all(), $this->rules('reset'), $this->messages('reset'));
            $title = 'Reset Password';

            if ($validator->fails()) {
                return redirect()->back(302, $request->headers->all())
                    ->withInput($request->input())
                    ->with('title', $title)
                    ->with('errors', $validator->errors());
            }

            $user->fill([
                'password' => Hash::make($request->get('password'))
            ])->save();
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => 'Password Changed!',
                'body' => 'You successfully changed your password!'
            ]);
            return redirect('/login', 200, $request->headers->all(), $request->secure());
        } else {
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
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
    public function logout(Request $request) {
        $request->session()->flush();
        Auth::logout();
        return redirect('/', 302, $request->headers->all());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function verify(Request $request) {
        if(!$request->has(['id', 'hash', 'expires'])) {
            return response(view('errors.405', compact('title')), 405, $request->headers->all());
        }

        $request->query = new ParameterBag();

        $id = intval($request->get('id', -1));
        try {
            $user = User::whereId($id)->firstOrFail();
        } catch (ModelNotFoundException $e) { # invalid user / hacking attempt;
            return response(view('errors.405', compact('title')), 405, $request->headers->all());
        }

        $hash = $request->get('hash', null);
        if (strcmp($hash, sha1($user->getEmailForVerification())) != 0) { # invalid user / hacking attempt;
            return response(view('errors.405', compact('title')), 405, $request->headers->all());
        }

        $expires = $request->get('expires', 0);
        if (strtotime($expires) > time()) { # link expired;
            $request->session()->flash('message', [
                'level' => 'warning',
                'heading' => 'We are sorry.',
                'body' => 'The verification link has expired. Please login to your account and try again.'
            ]);
            return redirect('/login', 302, $request->headers->all(), $request->secure());
        }

        $user->markEmailAsVerified();
        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => 'Verification Successful',
            'body' => 'You successfully verified your email!'
        ]);
        return redirect('login', 302, $request->headers->all(), $request->secure());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function reset(Request $request) {
        $title = 'Unauthorized';
        if(!$request->has(['id', 'hash', 'expires'])) {
            return response(view('errors.405', compact('title')), 405, $request->headers->all());
        }

        $request->query = new ParameterBag();

        $id = intval($request->get('id', -1));
        try {
            $user = User::whereId($id)->firstOrFail();
        } catch (ModelNotFoundException $e) { # invalid user / hacking attempt;
            return response(view('errors.405', compact('title')), 405, $request->headers->all());
        }

        $hash = $request->get('hash', null);
        if (strcmp($hash, sha1($user->getEmailForVerification())) != 0) { # invalid user / hacking attempt;
            return response(view('errors.405', compact('title')), 405, $request->headers->all());
        }

        $expires = $request->get('expires', 0);
        if (strtotime($expires) > time()) { # link expired;
            $request->session()->flash('message', [
                'level' => 'warning',
                'heading' => 'We are sorry.',
                'body' => 'The verification link has expired. Please login to your account and try again.'
            ]);
            return redirect('/login', 302, $request->headers->all(), $request->secure());
        }

        if (!Auth::check()) {
            Auth::setUser($user);
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
        }
        $title = 'Reset Password';
        return response(view('user.reset', compact('title', 'user')), 200, $request->headers->all());
    }

    /**
     * @param Request $request
     * @param int $step
     * @return Response
     */
    public function change(Request $request, int $step) {
        $title = 'Reset';
        if ($step == 1)
        { # get reset email
            $title .= ' - Step 1';
            $request->session()->put('reset_step', 1);
            $request->headers->set('Content-Type', 'text/html; charset=utf-8');
            $request->setMethod('GET');
            return response(view('user.change', compact('title')), 200, $request->headers->all());
        }
        elseif ($step == 2 && strtolower($request->method()) == 'post' && $request->has('email'))
        { # send reset email
            $title .= ' - Step 2';

            $validator = Validator::make($request->all(), $this->rules('change.2'), $this->messages('change.2'));
            if ($validator->fails())
            { # invalid email / hacking attempt
                $request->session()->put('reset_step', 2);
                $request->headers->set('Content-Type', 'text/html; charset=utf-8');
                $request->setMethod('GET');
                return redirect()->back(302, $request->headers->all())
                    ->withInput($request->input())
                    ->with('title', $title)
                    ->with('errors', $validator->errors());
            }

            $user = User::getUserByEmail($request->get('email'));
            $request->merge(['user' => $user]);
            $request->setUserResolver(function () use($user) {return $user;});

            if (!$user->hasVerifiedEmail())
            {
                $request->session()->flash('message', [
                    'level' => 'warning',
                    'heading' => 'Email not verified!',
                    'body' => sprintf("%s %s", 'You have not verified your email, so we can not send you the link yet.', 'Please check your inbox for the verification email.')
                ]);
                $request->session()->forget('reset_step');
                $request->headers->set('Content-Type', 'text/html; charset=utf-8');
                $request->setMethod('GET');
                return redirect('/login', 302, $request->headers->all());
            }

            $user->sendPasswordResetNotification($user->getPasswordResetToken());
            $request->session()->put('reset _step', 2);
            return response(view('user.change', compact('title', 'user')), 200);
        }
        elseif ($step == 3 && strtolower($request->method()) == 'post' &&  $request->has('code'))
        { # validate code
            $title .= ' - Step 3';
            $request->headers->set('Content-Type', 'text/html; charset=utf-8');
            $request->setMethod('GET');

            $validator = Validator::make($request->all(),
                $this->rules('change.3', ['email' => $request->user()->email]),
                $this->messages('change.3')
            );
            if ($validator->fails())
            { # invalid code / hacking attempt
                $request->session()->put('reset_step', 3);
                $request->headers->set('Content-Type', 'text/html; charset=utf-8');
                $request->setMethod('GET');
                return redirect()->back(302, $request->headers->all())
                    ->withInput($request->all())
                    ->with('title', $title)
                    ->with('errors', $validator->errors());
            }
            elseif ($request->user()->hasPasswordResetTokenExpired())
            {
                $request->session()->flash('message', [
                    'level' => 'warning',
                    'heading' => 'The Verification code has expired!',
                    'body' => 'Please try to reset your password again after 1 hour.'
                ]);
                $request->headers->set('Content-Type', 'text/html; charset=utf-8');
                $request->setMethod('GET');
                return redirect()->back(302, $request->headers->all())
                    ->withInput($request->all())
                    ->with('title', $title)
                    ->with('errors', new MessageBag(['code.expired', 'The Verification code has expired']));
            }

            $request->session()->forget('reset_step');
            return redirect('/login', 302, [], $request->secure());
        }
        else
        {
            $request->headers->set('Content-Type', 'text/html; charset=utf-8');
            $request->setMethod('GET');
            $request->session()->forget('reset_step');
            throw abort(404, '', $request->headers->all());
        }
    }
}
