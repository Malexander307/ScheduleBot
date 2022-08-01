<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|https://api.telegram.org/bot5405790775:AAFsgtRK-nnRgFOHO-qTDAVUCJHkzqxJjYk/setWebhook?url=https://ebc8-62-122-205-3.ngrok.io/api/test
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('test', [\App\Http\Controllers\TestController::class, 'test']);
Route::post('schedule', [\App\Http\Controllers\ScheduleController::class, 'getSchedule']);
