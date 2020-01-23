<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class PaginationRequest
 * @package App\Http\Requests
 * @deprecated
 */
class PaginationRequest extends FormRequest
{
    protected $method = 'GET';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    /**
     * @return string|void
     */
    public function fullUrl()
    {
        $parent = parent::fullUrl();
        $parsed = parse_url($parent);
        if (preg_match('/.*sessions\?page=([0-9]+)$/i', $parent, $matches) && isset($matches[1])) {
            $page = intval($matches[1]);
            return "{$parsed['scheme']}://{$parsed['host']}{$parsed['path']}/page/{$page}";
        }
        return $parent;
    }
}
