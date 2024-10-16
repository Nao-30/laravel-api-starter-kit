<?php

namespace App\Http\Resources\Foo;


use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class FooResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->getFirstMediaUrl('advertisement_main_image', 'watermarked')
        ];

        return $response;
    }
}
