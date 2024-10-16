<?php

use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\Auth\AuthenticationController;
use App\Http\Controllers\Api\FooController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::domain(env('APP_DOMAIN'))->group(
    function (): void {
        Route::controller(AuthenticationController::class)->group(
            function (): void {
                Route::post('login', 'login');
                Route::get('logout', 'logout')->middleware(['auth:sanctum']);
            }
        );

        Route::middleware(['auth:sanctum'])->group(
            function (): void {
                Route::prefix('foo')->controller(FooController::class)->group(function () {
                    Route::get('/', 'showAll');
                    Route::post('create', 'store');
                    Route::patch('update/{foo}', 'update');
                    Route::get('show/{foo}', 'show');
                    Route::delete('delete/{foo}', 'delete');
                });
            }
        );
    }
);
