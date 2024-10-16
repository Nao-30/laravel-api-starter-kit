<?php

namespace App\Services;

use App\Models\Foo;
use App\Repositories\Interfaces\FooRepositoryInterface;
use App\Utils\TeleLogger;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class FooService
{
    protected $fooRepository;

    public function __construct(FooRepositoryInterface $fooRepository)
    {
        $this->fooRepository = $fooRepository;
    }

    /**
     * Store the request data in the database.
     *
     * @param Request $request the HTTP request object
     * @throws ValidationException if the request data is invalid
     * @throws Exception if an error occurs during the storage process
     * @return mixed the stored foo object or null on failure
     */
    public function store($request)
    {
        DB::beginTransaction();

        // in case of validation errors from within the Service Use:
        // if (false) {
        //     throw ValidationException::withMessages([
        //         'title' => ['messages'],
        //     ]);
        // }
        try {
            // Store Foo table
            $foo = $this->fooRepository->create($request->validated());

            if ($request->has('image')) {
                $foo->addMedia($request->image)->toMediaCollection('image');
            }

            DB::commit();
            return $foo;
        } catch (Exception  $e) {
            DB::rollBack();
            TeleLogger::logException($e);
            Log::error($e->__toString());

            return null;
        }
    }

    /**
     * Update the request data in the database.
     *
     * @param Request $request the HTTP request object
     * @throws ValidationException if the request data is invalid
     * @throws Exception if an error occurs during the storage process
     * @return mixed the stored foo object or null on failure
     */
    public function update($request, Foo $foo)
    {
        DB::beginTransaction();
        try {
            // Update Foo table
            $this->fooRepository->update($foo, $request->validated());

            if ($request->has('image')) {
                $foo->addMedia($request->image)->toMediaCollection('image');
            }

            DB::commit();
            return $foo;
        } catch (Exception  $e) {
            DB::rollBack();
            TeleLogger::logException($e);
            Log::error($e->__toString());

            return null;
        }
    }

    /**
     * delete the request data in the database.
     *
     * @param Request $request the HTTP request object
     * @throws ValidationException if the request data is invalid
     * @throws Exception if an error occurs during the storage process
     * @return mixed the stored foo object or null on failure
     */
    public function delete($request, Foo $foo)
    {
        DB::beginTransaction();
        try {
            $this->fooRepository->delete($foo);

            DB::commit();
            return $foo;
        } catch (Exception  $e) {
            DB::rollBack();
            TeleLogger::logException($e);
            Log::error($e->__toString());

            return null;
        }
    }

    /**
     * show the request data in the database.
     *
     * @param Request $request the HTTP request object
     * @throws ValidationException if the request data is invalid
     * @throws Exception if an error occurs during the storage process
     * @return mixed the stored foo object or null on failure
     */
    public function show($request, Foo $foo)
    {
        DB::beginTransaction();
        try {
            // You can manipulate the query builder here before returning it
            // commit() will be called at the end of the function if there is any modification like counting views or adding activity log
            // DB::commit();
            return $foo;
        } catch (Exception  $e) {
            DB::rollBack();
            TeleLogger::logException($e);
            Log::error($e->__toString());

            return null;
        }
    }

    /**
     * show all data in the database.
     *
     * @param Request $request the HTTP request object
     * @throws ValidationException if the request data is invalid
     * @throws Exception if an error occurs during the storage process
     * @return mixed the stored foo object or null on failure
     */
    public function showAll($request)
    {
        DB::beginTransaction();
        try {

            $allowedSorts = ['created_at'];
            $allowedFilters = [AllowedFilter::partial('name')];
            $selectedColumns = [
                'id',
                'name'
            ];

            $data = QueryBuilder::for($this->fooRepository->className())
                ->defaultSort('-created_at')
                ->allowedSorts($allowedSorts)
                ->allowedFilters($allowedFilters)
                ->select($selectedColumns)
                ->paginate();

            DB::commit();
            return $data;
        } catch (Exception  $e) {
            DB::rollBack();
            TeleLogger::logException($e);
            Log::error($e->__toString());

            return null;
        }
    }
}
