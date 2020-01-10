<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class Session extends JsonResource
{
    /**
     * Transfowrm the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title_full,
            'course_id' => $this->course_id,
            'course_title' => $this->course_title,
            'instructions' => $this->instructions,
            'deadline_full' => $this->deadline_full,
            'status' => $this->status == '1' ? 'Enabled' : 'Disabled',
            'open_data' => Carbon::createFromTimestamp(strtotime($this->open_data))->format(config('constants.date.full')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

}
