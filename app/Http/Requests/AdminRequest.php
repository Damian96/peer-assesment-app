<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class AdminRequest extends Request
{
    protected $email;
    protected $password;
    protected $remember = FALSE;

    /*** @param array                $query      The GET parameters* @param array                $request    The POST parameters* @param array                $attributes The request attributes (parameters parsed from the PATH_INFO, ...)* @param array                $cookies    The COOKIE parameters* @param array                $files      The FILES parameters* @param array                $server     The SERVER parameters* @param string|resource|null $content    The raw body data*/
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string|max:50'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => 'email address',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Your email is required',
            'password.required' => 'Your password is required'
        ];
    }
}
