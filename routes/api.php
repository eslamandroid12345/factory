<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\DeveloperController;
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
|
*/

Route::group(['prefix' => 'developer'], function (){

  Route::post('login',[DeveloperController::class,'login']);
  Route::post('register',[DeveloperController::class,'register']);
  Route::post('logout',[DeveloperController::class,'logout']);
  Route::get('all/admins',[DeveloperController::class,'allAdmins']);
  Route::put('admin/block/{id}',[DeveloperController::class,'adminBlockById']);

});


Route::group(['prefix' => 'admins'], function (){

    Route::post('login',[AdminController::class,'login']);
    Route::post('register',[AdminController::class,'register']);
    Route::post('logout',[AdminController::class,'logout']);

});

Route::fallback(function (){

    return response()->json(['message' => 'url not found','code' => 404]);

});
