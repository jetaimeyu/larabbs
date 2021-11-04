<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TopicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'category_id' => (int)$this->category_id,
            'user_id' => (int)$this->user_id,
            'reply_count' => (int)$this->reply_count,
            'view_count' => (int)$this->view_count,
            'last_reply_user_id' => (int)$this->last_reply_user_id,
            'order' => (int)$this->order,
            'excerpt' => $this->excerpt,
            'slug' => $this->slug,
            'created_at' => (string)$this->created_at,
            'update_at'=>(string)$this->update_at,
            'user'=>new UserResource($this->whenLoaded('user')),
            'category'=>new CategoryResource($this->whenLoaded('category'))
        ];
    }
}