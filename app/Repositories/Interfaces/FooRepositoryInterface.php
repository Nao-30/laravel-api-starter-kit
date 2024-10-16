<?php

namespace App\Repositories\Interfaces;

interface FooRepositoryInterface
{
    public function create(array $data);
    public function update($foo, array $data);
    public function delete($foo);
    public function find($id);
    public function query();
    public function className();
    public function all();
}
