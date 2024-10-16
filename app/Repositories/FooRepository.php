<?php

namespace App\Repositories;

use App\Models\Foo;
use App\Repositories\Interfaces\FooRepositoryInterface;

class FooRepository implements FooRepositoryInterface
{
    public function create(array $data)
    {
        return Foo::create($data);
    }

    public function update($foo, array $data)
    {
        return $foo->update($data);
    }

    public function delete($foo)
    {
        return $foo->delete();
    }

    public function find($id)
    {
        return Foo::find($id);
    }

    public function query()
    {
        return Foo::query();
    }

    public function className()
    {
        return Foo::class;
    }

    public function all()
    {
        return Foo::all();
    }
}
