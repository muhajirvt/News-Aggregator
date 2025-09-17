<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Str;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description??"",
            'source' => Str::ucfirst($this->source_name) ?? "" ,
            'category' => Str::ucfirst($this->category_name) ?? "",
            'author' => Str::ucfirst($this->author_name) ?? "",
            'published_at' => Carbon::parse($this->published_at)->format('Y-m-d h:ia'),
        ];
    }
}
