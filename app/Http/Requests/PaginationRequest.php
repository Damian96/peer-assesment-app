<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

/**
 * Class PaginationRequest
 * @package App\Http\Requests
 */
class PaginationRequest extends Request
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
        return [];
    }

    /**
     * Get the full URL for the request.
     *
     * @return string
     */
    public function fullUrl()
    {
        $parent = parent::fullUrl();
        $parsed = parse_url($parent);
        if (preg_match('/.*courses\?page=([0-9]+).*/i', $parent, $matches) && isset($matches[1]))
            return sprintf("%s://%s%s/page/%d}", $parsed['scheme'], $parsed['host'], $parsed['path'], intval($matches[1]));
        return $parent;
    }
}
