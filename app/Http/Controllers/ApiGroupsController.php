<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Resources\GroupCollection;
use App\Session;
use Illuminate\Http\Request;

/**
 * Class ApiGroupsController
 * @package App\Http\Controllers
 */
class ApiGroupsController extends Controller
{
    private $headers = [
        'Content-Type' => 'application/json'
    ];

    /**
     * ApiGroupsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['api', 'auth.api']);
    }

    /**
     * Get the specified Session's Groups (/api/groups/{session}/all).
     * @param Request $request
     * @param Session $session
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSessionGroups(Request $request, Session $session)
    {
        $groups = new GroupCollection($session->groups()->getModels());
        $groups->additional(['session' => $session]);
        return response()->json($groups, 200, array_merge($this->headers, $request->session()->get('wpes_headers')));
    }

    /**
     * Get the specified Session's Groups (/api/groups/all).
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll(Request $request)
    {
        return response()->json(new GroupCollection(Group::all()), 200, array_merge($this->headers, $request->session()->get('wpes_headers')));
    }
}
