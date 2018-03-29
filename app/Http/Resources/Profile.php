<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'shares' => count($this->shares),
            'likes' => count($this->likes) + count($this->replylikes) + count($this->commentlikes),
            'comments' => count($this->comments) + count($this->replies)
        ];
    }
}
