<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usercontroller;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('rigester',[usercontroller::class,'Rigester']);
Route::post('login',[usercontroller::class,'login']);
Route::middleware(['auth:sanctum'])->group(function (){
Route::post('add_property',[usercontroller::class,'add_property']);
Route::post('updateuser',[usercontroller::class,'update']);
Route::post('logout',[usercontroller::class,'logout']);
});
Route::get('slider',[usercontroller::class,'showSlider']);
Route::get('getproperty/{id}',[usercontroller::class,'getproperty']);
Route::get('getproperty',[usercontroller::class,'property']);
Route::get('profile/{id}',[usercontroller::class,'profile']);
Route::get('public_search',[usercontroller::class,'public_search']);

