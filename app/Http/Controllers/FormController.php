<?php


namespace App\Http\Controllers;

use App\Course;
use App\Form;
use App\FormTemplate;
use App\Question;
use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class FormController extends Controller
{
    const PER_PAGE = 10;

    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('guest');
        $this->middleware('role');
        $this->middleware('verified');
    }

    /**
     * @param string $action
     * @param Request $request
     * @return array
     */
    public function rules(string $action, Request $request)
    {
        $rules = [];
        if ($request->has('question')) {
            foreach ($request->get('question', []) as $i => $q) {
                $rules['question.' . $i . '.type'] = 'required|string|in:multiple-choice,linear-scale,eval,paragraph,criteria';
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
        }
        switch ($action) {
            case 'duplicate':
                return [
                    '_method' => 'required|in:POST',
                    'session_id' => 'required|int|exists:sessions,id',
                ];
            case 'update':
                return array_merge($rules, [
                    'form_id' => 'required|int|exists:forms,id',
                    '_method' => 'required|in:POST',
                    'title' => 'required|string|min:15|max:255',
                    'subtitle' => 'nullable|string|min:15|max:255',
                    'footnote' => 'nullable|string|min:15|max:255',
                    'question' => 'required|array',
                ]);
            case 'store':
                return array_merge($rules, [
                    '_method' => 'required|in:POST',
                    'title' => 'required|string|max:255',
                    'subtitle' => 'nullable|string|max:255',
                    'footnote' => 'nullable|string|max:255',
                    'question' => 'required|array',
                ]);
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
                    'title.min' => 'The title should be at least 15 characters long!',
                    'title.max' => 'The title should be at most 255 characters long!',

                    'subtitle.required' => 'The Form should have a subtitle!',
                    'subtitle.string' => 'Invalid subtitle!',
                    'subtitle.min' => 'The subtitle should be at least 15 characters long!',
                    'subtitle.max' => 'The subtitle should be at most 255 characters long!',

                    'footnote.string' => 'Invalid footnote!',
                    'footnote.max' => 'The footnote should be at most 255 characters long!',

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

        $sessions = Auth::user()->sessions()->get();
        $messages = $this->messages(__FUNCTION__);
        return \response(view('forms.create', compact('messages', 'title', 'sessions')), 200);
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
            ->leftJoin('sessions', 'sessions.id', '=', 'forms.session_id')
            ->leftJoin('courses', 'courses.id', '=', 'sessions.course_id')
            ->where('courses.user_id', '=', Auth::user()->id)
            ->get([
                'forms.id',
                'forms.title',
                'sessions.id AS session_id',
                'courses.code',
                'sessions.title AS session_title',
                'courses.id AS course_id',
            ]);
        $templates = FormTemplate::whereUserId(Course::DUMMY_ID)
            ->orWhere('user_id', '=', Auth::user()->id)
            ->get(['form_templates.*']);
        $merged = new Collection(array_merge($templates->all(), $forms->all()));
        $total = $merged->count();
        $page = intval($request->get('page', 1));
        $first = $page > 1 ? $page * self::PER_PAGE : 1;
        $last = $first + $merged->count() - 1;
        $merged = $merged->forPage($page, self::PER_PAGE);
        $except = DB::table('forms')
            ->leftJoin('sessions', 'sessions.id', '=', 'forms.session_id')
            ->leftJoin('courses', 'courses.id', '=', 'sessions.course_id')
            ->where('courses.user_id', '=', Auth::user()->id)
            ->whereNotNull('sessions.id');
        $except = array_column($except->get('sessions.id')->toArray(), 'id');
        $sessions = Session::all();
        return response(view('forms.index', compact('title', 'sessions', 'merged', 'first', 'last', 'total', 'except')), 200, $request->headers->all());
    }

    /**
     * @method _POST
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @TODO: change form templates to their own table
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__, $request), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            return redirect()->back(302)
                ->withHeaders($request->headers->all())
                ->withInput($request->all())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        $form = new Form($request->all());
        $form->setAttribute('session_id', Course::DUMMY_ID);
        if (!$form->save() && env('APP_DEBUG', false)) {
            throw abort(500, sprintf("Could not insert Form in database"));
        } elseif (!env('APP_DEBUG', false)) {
            // fallback
            return redirect()->back(302)
                ->withHeaders($request->headers->all())
                ->withInput($request->all())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        foreach ($request->get('question', []) as $question) {
            $data = array_merge($question, [
                'form_id' => $form->id,
                'data' => [
                    'type' => isset($question['type']) ? $question['type'] : null,
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
                return redirect()->back(302)
                    ->withHeaders($request->headers->all())
                    ->withInput($request->all())
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
     * @param Form $form
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function update(Request $request, Form $form)
    {
        $validator = Validator::make($request->all(), $this->rules(__FUNCTION__, $request), $this->messages(__FUNCTION__));

        if ($validator->fails()) {
            $request->request->add(['cards' => $request->get('question', [])]);
            return redirect()->back(302)
                ->withInput($request->request->all())
                ->withErrors($validator->errors())
                ->with('errors', $validator->errors());
        }

        $form->fill([
            'title' => $request->get('title', $form->title),
            'subtitle' => $request->get('subtitle', $form->subtitle),
            'footnote' => $request->get('footnote', $form->footnote),
        ]);

        if (!$form->save()) {
            abort_unless(env('APP_DEBUG', false), 500, 'Could not save form!');
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => 'Could not save form!',
            ]);
            return redirect()->back(302, []);
        }

        foreach ($form->questions()->getModels() as $i => $question) {
            /**
             * @var Question $question
             */
            try {
                if (!isset($request->get('question')[$i]))
                    $question->delete();
                else {
                    $data = $request->get('question', [])[$i];
                    if (isset($data['id'])) {
                        $question->update([
                            'title' => $data['title'],
                            'data' => Question::extractData($data),
                        ]);
                    } else {
                        $question->fill([
                            'title' => $data['title'],
                            'data' => Question::extractData($data),
                        ]);
                        $question->save();
                    }
                }
            } catch (\Exception $e) {
                abort_unless(env('APP_DEBUG', false), 500, "Could not delete Question {$question->id}!");
                $request->session()->flash('message', [
                    'level' => 'danger',
                    'heading' => "Could not save/update/delete Question {$question->id}!",
                ]);
                return redirect()->back(302, []);
            }
        }

        foreach (array_slice($request->get('question'), $form->questions()->count()) as $q) {
            $question = new Question([
                'form_id' => $form->id,
                'data' => Question::extractData($q),
                'title' => $q['title']
            ]);
            try {
                $question->saveOrFail();
            } catch (\Throwable $e) {
                throw_if(env('APP_DEBUG', false), $e);
                $request->session()->flash('message', [
                    'level' => 'danger',
                    'heading' => "Could not delete Question {$question->id}!",
                ]);
                return redirect()->back(302, []);
            }
        }

        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => sprintf("Form %s saved successfully!", $form->id),
        ]);
        return redirect()->action('FormController@index', [], 302);
    }

    /**
     * @method _DELETE
     * @param Request $request
     * @param Form $form
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Throwable
     */
    public function delete(Request $request, Form $form)
    {
        throw_if($form->session_id == Course::DUMMY_ID, 403);

        if ($form->questions()->exists()) {
            foreach ($form->questions()->getModels() as $q) {
                $q->delete();
            }
        }

        if ($form->delete()) {
            $request->session()->flash('message', [
                'level' => 'success',
                'heading' => sprintf("Successfully delete Form: %s", $form->title),
            ]);
            return redirect()->back(302, $request->headers->all());
        }

        throw_if(env('APP_DEBUG', false), 500, sprintf("Could not delete form: %s", $form->title));
        $request->session()->flash('message', [
            'level' => 'danger',
            'heading' => sprintf("Could not delete Form: %s", $form->title),
        ]);
        return redirect()->back(302, $request->headers->all());
    }

    /**
     * @method _POST
     * @param Request $request
     * @param Form $form
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Throwable
     */
    public function duplicate(Request $request, FormTemplate $form)
    {
        $validator = Validator::make($request->all(), [], $this->rules(__FUNCTION__, $request));

        if ($validator->fails()) {
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => 'Could not duplicate Form!',
                'body' => 'Session does not exist!',
            ]);
            return redirect()->back(302);
        }

        // Duplicate form
        $original = $form;
        $form = new Form($original->getAttributes());
        $form->fill([
            'id' => null,
            'title' => $form->title . ' - duplicate',
            'session_id' => $request->get('session_id'),
            'mark' => 0
        ]);

        if (!$form->save()) {
            throw_if(env('APP_DEBUG', false), new InternalErrorException('Could not replicate form'));
            $request->session()->flash('message', [
                'level' => 'danger',
                'heading' => 'Could not duplicate Form!',
            ]);
            return redirect()->action('FormController@index', 302);
        }

        foreach ($original->questions() as $q) {
            $model = new Question($q);
            $model->fill([
                'form_id' => $form->id,
                'data' => json_encode($model->getAttribute('data'))
            ]);

            if (!$model->save()) {
                throw_if(env('APP_DEBUG', false), new InternalErrorException('Could not replicate form'));
                $request->session()->flash('message', [
                    'level' => 'danger',
                    'heading' => 'Could not duplicate Form!',
                ]);
                return redirect()->action('FormController@index', 302);
            }
        }

        $request->session()->flash('message', [
            'level' => 'success',
            'heading' => 'You successfully duplicated the Form!',
            'body' => sprintf("The new form is : %s", $form->id)
        ]);
        return redirect()->action('FormController@index', [], 302);
    }
}
