<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $p= parent::toArray($request);
        return array_merge($p,[
            'user'=>new UserResource($this->whenLoaded('user')),
            'topic'=>new TopicResource($this->whenLoaded('topic'))
        ]);
    }
}
