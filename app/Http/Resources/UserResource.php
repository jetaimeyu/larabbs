<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $showSensitiveFields = false;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (!$this->showSensitiveFields) {
            $this->resource->makeHidden(['phone', 'email','created_at', 'updated_at']);
        }
        $data = parent::toArray($request);

        $data['bound_phone'] = $this->resource->phone ? true : false;
        $data['bound_wechat'] = ($this->resource->weixin_openid || $this->resource->weixin_unionid) ? true : false;
        $data['roles']=  RoleResource::collection($this->whenLoaded('roles'));
        return $data;
    }

    public function showSensitiveFields()
    {
        $this->showSensitiveFields= true;
        return $this;
    }
}
