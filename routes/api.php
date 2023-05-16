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


});
Route::post('uploadeImage',[usercontroller::class,'upload_image']);
Route::get('slider',[usercontroller::class,'showSlider']);
