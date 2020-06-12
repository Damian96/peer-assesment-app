<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    const ACCEPTED = 202;

    private $headers = [
        'Content-Type' => 'application/json'
    ];

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->middleware('api');
        $this->middleware('auth.api')->except('login');
    }

    /**
     * @param $action
     * @return array
     */
    public function rules($action)
    {
        switch ($action) {
            case 'login':
                return [
                    'method' => 'required|string|in:_POST',
                    'email' => 'required|email|exists:users,email',
                    'password' => 'required|string|max:100',
                ];
            case 'sessionCollection':
                return [
//                    'method' => 'required|string|in:_GET',
                    'except' => 'nullable|string|regex:/([0-9],).+,?/i',
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
        $defaults = [
            'combo' => 'Invalid credentials combination.',
        ];
        switch ($action) {
            case 'login':
                return [
                    'email.required' => $defaults['combo'],
                    'password.required' => $defaults['combo'],
                    'method.required' => $defaults['combo'],
                ];
            default:
                return [];
        }
    }

    /**
     * @method _POST
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails())
            return response()->json(['message' => $validator->messages()->first(), 'error' => $validator->errors()->first()], 200, $this->headers);

        try {
            $user = User::whereEmail($request->get('email'))->firstOrFail();
            if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')]) && !$user->isStudent()) {
                $user->generateApiToken();
                Auth::setUser($user);
                Auth::guard('api')->setUser($user);
                return response()->json([
                    'token' => $user->api_token,
                    'user' => Auth::guard('api')->user(),
                ], 200, $this->headers);
            } else
                return response()->json(['message' => 'Invalid email-password combination provided.', 'error' => 'Could not authenticate.'], 200, $this->headers);
        } catch (\Throwable $e) {
            throw_if(env('APP_DEBUG', false), $e, ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Invalid email-password combination provided.', 'code' => $e->getCode()], 200, $this->headers);
        }
    }
}
