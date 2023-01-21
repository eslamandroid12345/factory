<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [

            'id' => $this->id,
            'factory_name' => $this->factory_name,
            'email' => $this->email,
            'factory_owner' => $this->factory_owner,
            'factory_phone' => $this->factory_phone,
            'commercial_registration' => $this->commercial_registration,
            'last_seen' => Carbon::parse($this->last_seen)->format('Y-m-d H'),
            'access_days' => $this->access_days,
            'status' => $this->status,
            'developer' => new DeveloperResource($this->developer),
            'image' => $this->image !== 'avatar.jpg' ? asset('admins/' . $this->image): asset('image/avatar.jpg'),
            'token' => 'Bearer ' . $this->token,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),

        ];
    }
}
