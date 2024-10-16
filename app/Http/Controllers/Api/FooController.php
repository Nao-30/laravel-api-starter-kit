<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Foo\DeleteFooRequest;
use App\Http\Requests\Foo\ShowAllFooRequest;
use App\Http\Requests\Foo\ShowFooRequest;
use App\Http\Requests\Foo\StoreFooRequest;
use App\Http\Requests\Foo\UpdateFooRequest;
use App\Http\Resources\Foo\FooCollection;
use App\Http\Resources\Foo\FooResource;
use App\Models\Foo;
use App\Services\FooService;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\ResponseFromFile;

#[Group("Foos API")]
class FooController extends Controller
{
    protected $service;



    public function __construct(FooService $fooService)
    {
        $this->service = $fooService;
    }

    /**
     * create a new Foo
     *
     * @param  StoreFooRequest  $request
     * @return \Illuminate\Http\Response
     */
    #[Authenticated()]
    #[ResponseFromFile("storage/app/responses/foo/foo.store.json", 200, [], 'Data Created Successfully')]
    public function store(StoreFooRequest $request)
    {
        $foo = $this->service->store($request);
        return null !== $foo ? $this->respondWithResource(new FooResource($foo), "تمت العملية", 201) : $this->respondInternalError("حدث خطأ اثناء العملية");
    }

    /**
     * update an existing Foo
     *
     * @param  UpdateFooRequest  $request
     * @return \Illuminate\Http\Response
     */
    #[Authenticated()]
    #[ResponseFromFile("storage/app/responses/foo/foo.update.json", 200, [], 'Data Updated Successfully')]
    public function update(UpdateFooRequest $request, Foo $foo)
    {
        $foo = $this->service->update($request, $foo);
        return null !== $foo ? $this->respondWithResource(new FooResource($foo), "تمت العملية", 201) : $this->respondInternalError("حدث خطأ اثناء العملية");
    }

    /**
     * View Foo
     *
     * @param  ShowFooRequest  $request
     * @return \Illuminate\Http\Response
     */
    #[Authenticated()]
    #[ResponseFromFile("storage/app/responses/foo/foo.show.json", 200, [], 'Data Retrieved Successfully')]
    public function show(ShowFooRequest $request, Foo $foo)
    {
        $foo = $this->service->show($request, $foo);
        return null !== $foo ? $this->respondWithResource(new FooResource($foo), "تمت العملية") : $this->respondInternalError("حدث خطأ اثناء العملية");
    }

    /**
     * View All Foo
     *
     * @param  ShowAllFooRequest  $request
     * @return \Illuminate\Http\Response
     */
    #[Authenticated()]
    #[ResponseFromFile("storage/app/responses/foo/foo.show.all.json", 200, [], 'Data Retrieved Successfully')]
    public function showAll(ShowAllFooRequest $request)
    {
        $foo = $this->service->showAll($request);
        return null !== $foo ? $this->respondWithResourceCollection(new FooCollection($foo->items()), $foo, 'Foos displayed successfully') : $this->respondInternalError("حدث خطأ اثناء العملية");
    }

    /**
     * Delete Foo
     *
     * @param  DeleteFooRequest  $request
     * @return \Illuminate\Http\Response
     */
    #[Authenticated()]
    #[Response([], 204, 'Deleted Successfully')]
    public function delete(DeleteFooRequest $request, Foo $foo)
    {
        $foo = $this->service->delete($request, $foo);
        return null !== $foo ? $this->respondNoContentStatus() : $this->respondInternalError("حدث خطأ اثناء العملية");
    }
}
