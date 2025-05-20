<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Services\DateFormatter;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'title'=>$this->title,
            'description'=>$this->description,
            "type"=>$this->whenLoaded('noticeType'),
            "site"=>$this->whenLoaded('site'),
            'created_at'=>app(DateFormatter::class)->formatToUserTimezone($this->created_at),
            'is_solved'=>$this->is_solved
        ];
    }
}
