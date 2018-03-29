<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\KeywordsResource;

class ArticleResource extends JsonResource
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
            'title' => $this->title,
            'file' => $this->file,
            'body' => $this->body,
            'category' => $this->category->name,
            'keywords' => KeywordsResource::collection($this->keywords)
        ];
    }
}
