<?php

namespace App\Http\Resources\Foo;

use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

final class FooCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            // you can customize the resource here before returning, like returning only needed data
            // $data = [
            //     'id' => $item->id,
            //     'name' => $item->name,
            //     'address' => $item->address,
            // ];
            // return $data;

            return $item;
        });
    }
}
