<?php

use App\Http\Controllers\Api\V1\Auth;
use App\Http\Controllers\Api\V1\Consultations;
use App\Http\Controllers\Api\V1\Spots;
use App\Http\Controllers\Api\V1\Vaccinations;
use App\Http\Middleware\AuthApi;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function(){
    Route::prefix('auth')->group(function(){
        Route::post('login',[Auth::class,'login']);
        Route::post('logout',[Auth::class,'logout']);
    });

    Route::prefix('consultations')->middleware([AuthApi::class])->group(function(){
        Route::get('',[Consultations::class,'consultations']);
        Route::post('',[Consultations::class,'request_consultations']);
    });

    Route::prefix('spots')->middleware([AuthApi::class])->group(function(){
       Route::get('',[Spots::class,'spot_vaccines']);
       Route::get('{spot_id}',[Spots::class,'spot_detail']);
    });

    Route::prefix('vaccinations')->middleware([AuthApi::class])->group(function(){
        Route::post('',[Vaccinations::class,'registrasi']);
        Route::get('',[Vaccinations::class,'get_data']);
    });
});
