<?php

namespace App\Http\Controllers;

use App\User;
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
        $this->middleware('auth:api')->except('login');
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
        return [];
    }

    /**
     * @method _POST
     * @param Request $request
     * @return string
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            $user = User::getUserByEmail($request->get('email'));
            if (!$user->isStudent()) {
                $user->generateApiToken();
                Auth::guard('api')->setUser($user);
                return $this->sendResponse([
                    'token' => $user->api_token,
                    'user' => Auth::guard('api')->user(),
                ]);
            }
        }

        return $this->sendResponse("error");
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
            return \response(json_encode(['error']), 200);
        }
        return $json ? \response($json, 200) : \response(json_encode(['error']), 200,
            [
                'Content-Type' => 'application/json',
            ]);
    }

}
