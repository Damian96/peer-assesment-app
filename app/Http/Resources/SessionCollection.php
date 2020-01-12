<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SessionCollection extends ResourceCollection
{
    /**
     * The "data" wrapper that should be applied.
     *
     * @var string
     */
    public static $wrap = null;

    /**
     * The additional meta data that should be added to the resource response.
     *
     * Added during response construction by the developer.
     *
     * @var array
     */
    public $additional = [
        'mymeta' => 'iamjustadev',
    ];

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    /**
     * Resolve the resource to an array.
     *
     * @param \Illuminate\Http\Request|null $request
     * @return array
     */
//    public function resolve($request = null) {}

    /**
     * Add additional meta data to the resource response.
     *
     * @param array $data
     * @return $this
     */
//    public function additional(array $data) {}
}
