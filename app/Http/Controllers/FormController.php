<?php


namespace App\Http\Controllers;

use App\Form;
use App\Question;
use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    const PER_PAGE = 10;

    /**
     * @param string $action
     * @param Request $request
     * @return array
     */
    public function rules(string $action, Request $request)
    {
        switch ($action) {
            case 'update':
                $rules = [
                    'form_id' => 'required|int|exists:forms,id',
                    '_method' => 'required|in:POST',
                    'session_id' => 'required|int|exists:sessions,id',
                    'title' => 'required|string|max:255',
                    'subtitle' => 'nullable|string|max:255',
                    'footnote' => 'nullable|string|max:255',
                    'question' => 'required|array',
                ];
                foreach ($request->get('question', []) as $i => $q) {
                    $rules['question.' . $i . '.type'] = 'required|string|in:multiple-choice,linear-scale,eval,paragraph';
                    $rules['question.' . $i . '.title'] = 'required|string|max:255';
                    $rules['question.' . $i . '.subtitle'] = 'nullable|string|max:255';
                    if ($q['type'] === 'multiple-choice') {
                        $rules['question.' . $i . '.choices'] = 'required|array';
                    } elseif ($q['type'] === 'linear-scale') {
                        $rules['question.' . $i . '.max'] = 'required|int|min:1|max:100';
                        $rules['question.' . $i . '.minlbl'] = 'required|string|max:255';
                        $rules['question.' . $i . '.maxlbl'] = 'required|string|max:255';
                    }
                }
                return $rules;
            case 'store':
                $rules = [
                    '_method' => 'required|in:POST',
                    'session_id' => 'required|int|exists:sessions,id',
                    'title' => 'required|string|max:255',
                    'subtitle' => 'nullable|string|max:255',
                    'footnote' => 'nullable|string|max:255',
                    'question' => 'required|array',
                ];
                foreach ($request->get('question', []) as $i => $q) {
                    $rules['question.' . $i . '.type'] = 'required|string|in:multiple-choice,linear-scale,eval,paragraph';
                    $rules['question.' . $i . '.title'] = 'required|string|max:255';
                    $rules['question.' . $i . '.subtitle'] = 'nullable|string|max:255';
                    if ($q['type'] === 'multiple-choice') {
                        $rules['question.' . $i . '.choices'] = 'required|array';
                    } elseif ($q['type'] === 'linear-scale') {
                        $rules['question.' . $i . '.max'] = 'required|int|min:1|max:100';
                        $rules['question.' . $i . '.minlbl'] = 'required|string|max:255';
                        $rules['question.' . $i . '.maxlbl'] = 'required|string|max:255';
                    }
                }
                return $rules;
            default:
                return [];
        }
    }

    /**
     * @param string $action
     * @return array
     */
    public function messages(string $action)
    {
        switch ($action) {
            case 'create':
            case 'store':
            case 'update':
                return [
                    'session_id' => '',

                    'title.required' => 'The Form should have a title!',
                    'title.string' => 'The Form should have a title!',
                    'title.max' => 'The title should be at most 255 characters long!',

                    'subtitle.required' => 'The Form should have a title!',
                    'subtitle.string' => 'Invalid title!',
                    'subtitle.max' => 'The title should be at most 255 characters long!',

                    'footnote.string' => 'Invalid title!',
                    'footnote.max' => 'The title should be at most 255 characters long!',

                    'question.required' => 'The Form should have questions!',
                    'question.array' => 'The Form should have questions!',
                ];
            default:
                break;
        }
    }

    /**
     * @method _GET
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $title = 'Add Form Template';
        $sessions = Session::all();
        return \response(view('forms.create', compact('title', 'sessions')), 200);
    }

    /**
     * @method _GET
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = 'My Form Templates';
        $forms = DB::table('forms')
            ->join('sessions', 'sessions.id', '=', 'forms.session_id')
            ->join('courses', 'courses.id', '=', 'sessions.course_id')
            ->where('courses.user_id', '=', Auth::user()->id)
            ->whereNotNull('sessions.id')
            ->select([
                'forms.*',
                'courses.code',
                'courses.id AS course_id',
                'courses.title AS course_title',
                'sessions.title AS session_title',
            ])
            ->paginate(self::PER_PAGE);
//        return dd($forms);
        return response(view('forms.index', compact('title', 'forms')), 200, $request->headers->all());
    }

    /**
     * @method _POST
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__, $request), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            return redirect()->back(302)
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        $form = new Form($request->all());
        if (!$form->save() && env('APP_DEBUG', false)) {
            throw abort(500, sprintf("Could not insert Form in database"));
        } elseif (!env('APP_DEBUG', false)) {
            return redirect()->back(302) // fallback
            ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        foreach ($request->get('question', []) as $question) {
            $data = array_merge($question, [
                'form_id' => $form->id,
                'data' => [
                    'max' => isset($question['max']) ? $question['max'] : null,
                    'minlbl' => isset($question['minlbl']) ? $question['minlbl'] : null,
                    'maxlbl' => isset($question['maxlbl']) ? $question['maxlbl'] : null,
                    'choices' => isset($question['choices']) ? $question['choices'] : null,
                ],
            ]);
            $question = new Question($data);
            if (!$question->save() && env('APP_DEBUG', false)) {
                throw abort(500, sprintf("Could not insert Question in database"));
            } else if (!env('APP_DEBUG', false)) {
                return redirect()->back(302) // fallback
                ->withErrors($validator->errors())
                    ->with('errors', $validator->errors());
            }
        }

        $request->session()->flash('message', [
            'heading' => 'Form saved successfully!',
            'level' => 'success'
        ]);
        return redirect()->route('form.index');
    }

    /**
     * @method _GET
     * @param Request $request
     * @param Form $form
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, Form $form)
    {
        $title = 'Edit Form ' . $form->id;
        return response(view('forms.edit', compact('title', 'form')), 200, $request->headers->all());
    }

    /**
     * @method _POST
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__, $request), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            $request->request->add(['cards' => $request->get('question', [])]);
            return redirect()->back(302)
                ->withInput($request->request->all())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }
    }
}
