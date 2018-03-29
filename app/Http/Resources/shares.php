<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class shares extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'articleid' => $this->article->id,
            'title' => $this->article->title,
            'body' => $this->article->body
        ];
    }
}
