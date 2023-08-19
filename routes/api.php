<?php

use App\Http\Controllers\adminController;
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



Route::middleware(['auth:sanctum'])->group(function (){
Route::post('add_property',[propertyController::class,'add_property']);// True
Route::post('updateuser',[usercontroller::class,'update']); // True
Route::post('logout',[usercontroller::class,'logout']);// True
Route::post('favorate/{id}',[usercontroller::class,'addToFavorate']);// True
Route::post('addRate',[usercontroller::class,'addRate']);// True
Route::post('profile_me',[usercontroller::class,'profile_me']);// True
Route::post('bank_account',[BankController::class,'create_bank_account']);// True
Route::post('edit_property',[propertyController::class,'edit_property']);
Route::post('delete_favorate',[usercontroller::class,'delete_favorate']); // True
Route::get('show_favorate',[usercontroller::class,'show_favorate']); // True
Route::get('show_my_account',[BankController::class,'show_my_account']); // True
Route::post('recharge_my_account',[BankController::class,'recharge_my_account']);// True
Route::post('buy_property',[BankController::class,'buy']); // True
Route::post('rent_property',[BankController::class,'rent']); // True
Route::post('inform',[adminController::class,'inform']);
});

Route::post('rigester',[usercontroller::class,'Rigester']); // True
Route::post('rigester1',[adminController::class,'Rigester']);
Route::post('login1',[adminController::class,'login']);
Route::post('login',[usercontroller::class,'login']); // True
Route::get('slider',[propertyController::class,'showSlider']); // True
Route::get('getproperty/{id}',[propertyController::class,'getproperty']);// Not in Use
Route::get('getproperty',[propertyController::class,'property']); // True
Route::get('profile/{id}',[usercontroller::class,'profile']);   //  True 
Route::post('public_search',[usercontroller::class,'public_search']); //  True 
Route::post('addBank',[BankController::class,'add_bank']); // True 
Route::post('filters',[usercontroller::class,'filters']); // True
Route::get('all_user',[usercontroller::class,'index']);

Route::get('auth/google',[usercontroller::class,'redirect_google']);

Route::any('auth/google/callback',[usercontroller::class,'handleCallback']);
