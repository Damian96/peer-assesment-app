<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        $this->middleware('guest')->except(['logout', 'verify', 'forgot', 'forgotSend', 'reset']);
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
            case 'forgot':
                return [
                    'email' => 'required|email|regex:/^.+@citycollege\.sheffield\.eu$/im|exists:users',
                ];
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
            case 'forgot':
                return [
                    'email.required' => 'We need to know your e-mail address!',
                    'email.regex' => sprintf('%s', 'The e-mail must be an academic one!'),
                    'email.exists' => 'Invalid e-mail address.',
                ];
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
     * Verify an email link, and redirect to /reset or verify user's email
     * @param Request $request
     * @return Response
     */
    public function verify(Request $request) {
        if(!$request->has(['id', 'hash', 'expires', 'action']) || !in_array($request->get('action', false), ['email', 'password'], true)) {
            return response(view('errors.405', compact('title')), 405, $request->headers->all());
        }

        $request->query = new ParameterBag();

        $id = intval($request->get('id', -1));
        try {
            $user = User::whereId($id)->firstOrFail()->refresh();
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
                'body' => $request->get('action', false) == 'email' ?
                    'The verification link has expired. Please login to your account and try again.' :
                    'The link has expired. Please try again.'
            ]);
            return redirect()->to('user.login', 302, $request->headers->all(), $request->secure());
        }

        if ($request->get('action', false) == 'email') {
            $user->markEmailAsVerified();
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => 'Verification Successful',
                'body' => 'You successfully verified your email!'
            ]);
            return redirect()->to('user.login', 302, $request->headers->all(), $request->secure());
        } elseif ($request->get('action', false) == 'password') {
            $request->setUserResolver(function () use ($user) { return $user; });
            $request->merge(['user' => $user]);
            return redirect()->to('user.reset',302, $request->headers->all(), $request->secure());
        } else {
            throw abort(404);
        }
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function reset(Request $request) {
        abort_if(!$request->has('user'), 405);

        $title = 'Reset Password';
        $user = $request->getUserResolver();
        return \response(view('user.reset', compact('title', 'user')), 200, $request->headers->all());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function forgot(Request $request) {
        $title = 'Forgot Password';
        return \response(view('user.forgot', compact('title')), 200, $request->headers->all());
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function forgotSend(Request $request) {
        $validator = Validator::make($request->all(), $this->rules('forgotSend'), $this->messages('forgotSend'));

        if ($validator->fails()) {
            return redirect()->action('UserController@forgot', [], 302)
                ->withInput($request->all())
                ->with('messages', $this->messages('forgotSend'))
                ->with('errors', $validator->errors());
        }

        $user = User::getUserByEmail($request->get('email'));
        $user->sendPasswordResetNotification($user->getPasswordResetToken());
        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => 'Reset Email Sent Successfully!',
            'body' => 'Check you inbox for our email, and follow the instructions to reset your password.'
        ]);
        return redirect()->action('UserController@login', [], 302);
    }
}
