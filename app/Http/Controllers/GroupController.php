<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class GroupController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('role');
        $this->middleware('verified');
    }

    /**
     * @param $action
     * @return array
     */
    private function rules($action)
    {
        switch ($action) {
            case 'storeMark':
                return [
                    '_method' => 'required|in:POST',
                    'mark' => 'required|numeric|min:1|max:100',
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
            case 'storeMark':
                return [
                    'mark.required' => 'The Group\'s mark is required!',
                    'mark.numeric' => 'The Group\'s mark is invalid!',
                    'mark.min' => 'The Group\'s mark should be at least 1!',
                    'mark.max' => 'The Group\'s mark should be at most 100!',
                ];
            default:
                return [];
        }
    }

    /**
     * @param Group $group
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $title = "Group {$group->name}";
        return response(view('group.show', compact('title', 'group')), 200);
    }

    /**
     * @param Group $group
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $title = "Edit Group {$group->name}";
        return response(view('group.show', compact('title', 'group')), 200);
    }

    /**
     * @param Request $request
     * @param Group $group
     * @return \Response|RedirectResponse
     * @throws \Throwable
     */
    public function storeMark(Request $request, Group $group)
    {
        $validator = \Validator::make($request->all(), $this->rules(__FUNCTION__), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            throw_if(env('APP_DEBUG', false), new InternalErrorException($validator->errors()->first()));
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => 'Could not store the Group\'s mark!',
                'body' => $validator->errors()->first()
            ]);
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        try {
            $group->mark = $request->get('mark', 0);
            $group->saveOrFail();
        } catch (\Throwable $e) {
            throw_if(env('APP_DEBUG', false), $e);
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => 'Oops!',
                'body' => "Something went wrong while marking Group {$group->title}!",
            ]);
            return redirect()->back()
                ->withInput($request->all())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => "Successfully marked Group {$group->id}!",
            'body' => $validator->errors()->first()
        ]);
        return redirect()->back()
            ->withInput($request->all());
    }
}
