<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\usercontroller;
use App\Http\Controllers\BankController;
use App\Http\Controllers\propertyController;


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
Route::post('add_property',[propertyController::class,'add_property']);
Route::post('updateuser',[usercontroller::class,'update']);
Route::post('logout',[usercontroller::class,'logout']);
Route::post('favorate/{id}',[usercontroller::class,'addToFavorate']);
Route::post('addRent',[usercontroller::class,'addRent']);
Route::post('profile_me',[usercontroller::class,'profile_me']);
Route::post('bank_account',[BankController::class,'create_bank_account']);
Route::post('edit_property',[propertyController::class,'edit_property']);
Route::post('delete_favorate',[usercontroller::class,'delete_favorate']);
Route::post('show_favorate',[usercontroller::class,'show_favorate']);
Route::get('show_my_account',[BankController::class,'show_my_account']);
Route::post('recharge_my_account',[BankController::class,'recharge_my_account']);
Route::post('buy_property',[BankController::class,'buy']);
Route::post('rent_property',[BankController::class,'rent']);



});
Route::get('slider',[propertyController::class,'showSlider']);
Route::get('getproperty/{id}',[propertyController::class,'getproperty']);
Route::get('getproperty',[propertyController::class,'property']);
Route::get('profile/{id}',[usercontroller::class,'profile']);
Route::post('public_search',[usercontroller::class,'public_search']);
Route::post('addBank',[BankController::class,'add_bank']);
Route::post('filters',[usercontroller::class,'filters']);
Route::get('all_user',[usercontroller::class,'index']);

Route::get('auth/google',[usercontroller::class,'redirect_google']);

Route::any('auth/google/callback',[usercontroller::class,'handleCallback']);
