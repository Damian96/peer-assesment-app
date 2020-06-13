<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Resources\SessionCollection;
use App\Session;
use Illuminate\Http\Request;

/**
 * Class ApiSessionsController
 * @package App\Http\Controllers
 */
class ApiSessionsController extends Controller
{
    private $headers = [
        'Content-Type' => 'application/json'
    ];

    /**
     * ApiSessionsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['api', 'auth.api']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        if ($request->has('except'))
            $ids = array_map('intval', explode(',', $request->get('except', '')));
        else
            $ids = [];

        return response()->json(new SessionCollection(Session::all()->whereNotIn('id', $ids)), 200, $this->headers);
    }

    /**
     * @param Request $request
     * @param Session $session
     * @return \Illuminate\Http\Response
     */
    public function getSessionForm(Request $request, Session $session)
    {
        /**
         * @var \App\Session $session
         */
        $groups = $session->groups()->getModels();
        $select = html()->select('group_id')->addClass('form-control-md')
            ->attribute('required', 'true')
            ->attribute('aria-required', 'true');

        foreach ($groups as $model) {
            /**
             * @var \App\Group $model
             */
            $select = $select->addChild(html()->option($model->name, $model->id));
        }

        $output = html()->form('POST', url("/sessions/{$session->id}/join-group"))
            ->addChild(html()->div(html()->label('Group')->addClass('form-control-md mr-2'))
                ->addClass('form-group text-center mt-1')
                ->addChild($select)
                ->addChild(html()->span()->attribute('class', 'invalid-feedback d-block'))
                ->addChild(html()->input('hidden', 'session_id', $session->id))
                ->addChild(html()->input('hidden', '_method', 'POST'))
                ->addChild(csrf_field()));

        return response($output, 200, ['Content-Type' => 'text/html'])->send();
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function checkSessions(Request $request)
    {
        $closed = Session::whereDeadline(now()->format('Y-m-d 00:00:00.000000'))
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

        $response['stats']['marked'] = $total;
        return response()->json($response, 200, $this->headers);
    }
}
