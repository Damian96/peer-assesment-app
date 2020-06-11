<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Resources\SessionCollection;
use App\Session;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    const ACCEPTED = 202;

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
                Auth::setUser($user);
                Auth::guard('api')->setUser($user);
                return $this->sendResponse([
                    'token' => $user->api_token,
                    'user' => Auth::guard('api')->user(),
                ]);
            } else {
                return $this->sendResponse(['message' => 'Invalid email-password combination provided.', 'error' => 'Could not authenticate.'], 200);
            }
        } catch (\Throwable $e) {
            throw_if(env('APP_DEBUG', false), $e, ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return $this->sendResponse(['message' => 'Invalid email-password combination provided.', 'code' => $e->getCode()]);
//            return $this->sendResponse(['message' => $e->getMessage(), 'code' => $e->getCode(), 'error' => $e->getTraceAsString()]);
        }
    }

    /**
     * /api/sessions/all
     * @param Request $request
     * @param string $except
     * @return SessionCollection|\Illuminate\Http\Response
     */
    public function sessionCollection(Request $request, string $except = '')
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));
        if ($validator->fails()) {
            return $this->sendResponse(['message' => $validator->messages()->first(), 'error' => $validator->errors()->first()]);
        }

        $except = explode(',', $except);
        $except = array_map(function ($value) {
            return intval($value);
        }, $except);
        return $this->sendResponse(new SessionCollection(Session::whereNotIn('sessions.id', $except)
            ->where('sessions.id', '!=', \App\Course::DUMMY_ID)
            ->get('sessions.*')), 200);
    }

    /**
     * URL:/api/sessions/{session}all
     * @param Request $request
     * @param Session $session
     * @return \App\Http\Resources\GroupCollection|\Illuminate\Http\Response
     */
    public function groupsOfSession(Request $request, Session $session)
    {
        $groups = new \App\Http\Resources\GroupCollection($session->groups()->getModels());
        $groups->additional(['session' => $session]);
        return $this->sendResponse($groups);
    }

    /**
     * URL:/api/groups/{session}/form
     * @param Request $request
     * @param Session $session
     * @return \Spatie\Html\Elements\Form
     * Content-Type: text/html
     */
    public function formOfSessionsGroups(Request $request, Session $session)
    {
        /**
         * @var \App\Session $session
         */
        $groups = $session->groups()->getModels();
        $select = html()->select('group_id')->addClass('form-control-md')->attribute('required', 'true')->attribute('aria-required', 'true');

        foreach ($groups as $model) {
            /**
             * @var \App\Group $model
             */
            $select = $select->addChild(html()->option($model->name, $model->id));
        }

        return html()->form('POST', url("/sessions/{$session->id}/join-group"))
            ->addChild(html()->div(html()->label('Group')->addClass('form-control-md mr-2'))->addClass('form-group text-center mt-1')
                ->addChild($select)
                ->addChild(html()->span()->attribute('class', 'invalid-feedback d-block'))
                ->addChild(html()->input('hidden', 'session_id', $session->id))
                ->addChild(html()->input('hidden', '_method', 'POST'))
                ->addChild(csrf_field()));
    }

    /**
     * URL:/api/groups/all
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function groups(Request $request)
    {
        return $this->sendResponse(new \App\Http\Resources\GroupCollection(\App\Group::all()->collect()));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function checkSessions(Request $request)
    {
        $closed = \App\Session::whereDeadline(now()->format('Y-m-d 00:00:00.000000'))
            ->getModels();

        $skipped = 0;
        $total = 0;
        $response = ['sessions' => []];
        foreach ($closed as $session) {
            /**
             * @var \App\Session $session
             */
            $marks = [];
            foreach ($session->groups()->getModels() as $group) {
                /**
                 * @var \App\Group $group
                 */
                if (!$group instanceof Group) continue;

                foreach ($group->students()->get(['users.*']) as $student) {
                    /**
                     * @var \App\User $student
                     */
                    if ($group->mark && $student->session()->where('session_id', '=', $session->id)->exists()) {
                        $marks[$group->id] = $group;
                    } else {
                        $skipped++;
                    }
                }
            }

            $marked = count($marks);
            if ($marked > 0) {
                $session->mark_avg = array_sum($marks) / $marked;
                $session->saveOrFail();
            }

            array_push($response['sessions'], [
                'session' => (object)$session->toArray(),
                'stats' => (object)[
                    'marked' => count($marks),
                    'skipped' => $skipped
                ]
            ]);
            $skipped = 0;
            $total += $marked;
        }

        $response['stats'] = [
            'marked' => $total
        ];
        return $this->sendResponse($response, 200);
    }

    /**
     * @param mixed $data
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    private function sendResponse($data, $code = 200)
    {
        try {
            $json = json_encode($data);
            return \response($json, $code, [
                'Content-Type' => 'application/json'
            ]);
        } catch (\Exception $e) {
            return \response(json_encode(['message' => $e->getMessage(), 'code' => $e->getCode(), 'trace' => $e->getTraceAsString()]), $code, [
                'Content-Type' => 'application/json'
            ]);
        }
    }
}
