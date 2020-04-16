<?php

namespace App\Http\Controllers;

use App\User;
use http\Exception\RuntimeException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->middleware('api')->except('login');
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
                    'password' => 'required|string|max:255',
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
        switch ($action) {
            case 'login':
                return [];
            default:
                return [];
        }
    }

    /**
     * /api/user/login
     * @method _POST
     * @param Request $request
     * @return string
     * @throws \Throwable
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails()) {
            return $this->sendResponse(['message' => $validator->messages()->first(), 'error' => $validator->errors()->first()]);
        }

        try {
            $user = User::whereEmail($request->get('email'))->firstOrFail();
            if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')]) && !$user->isStudent()) {
                $user->generateApiToken();
                Auth::guard('api')->setUser($user);
                return $this->sendResponse([
                    'token' => $user->api_token,
                    'user' => Auth::guard('api')->user(),
                ]);
            }
        } catch (\Throwable $e) {
            throw_if(env('APP_DEBUG', false), $e);
            return $this->sendResponse(['message' => $e->getMessage(), 'code' => $e->getCode(), 'error' => $e->getTraceAsString()]);
        }

        // fallback
        throw new \RuntimeException("Something went wrong!", 500);
    }

    /**
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function error404(\Exception $exception)
    {
        return $this->sendResponse(['message' => $exception->getMessage(), 'code' => $exception->getCode(), 'error' => $exception->getTraceAsString()]);
    }

    /**
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function error500(\Exception $exception)
    {
        return $this->sendResponse(['message' => $exception->getMessage(), 'code' => $exception->getCode(), 'error' => $exception->getTraceAsString()]);
    }

    /**
     * @param mixed $data
     * @return \Illuminate\Http\Response
     */
    private function sendResponse($data)
    {
        try {
            $json = json_encode($data, JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return \response(json_encode(['error']), 200, [
                'Content-Type' => 'application/json'
            ]);
        }
        return $json ? \response($json, 200, [
            'Content-Type' => 'application/json'
        ]) : \response(json_encode(['error']), 200,
            [
                'Content-Type' => 'application/json',
            ]);
    }

}
