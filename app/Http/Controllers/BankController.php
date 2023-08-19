<?php

namespace App\Http\Controllers;

use App\Models\location_model;
use App\Models\property_special_model;
use App\Models\state_model;
use App\Models\favorate_model;
use App\Models\rate_property_model;
use App\Models\Bank_model;
use App\Models\Account_bank;
use App\Models\selles_model;
use App\Models\rents_model;



use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Passport\HasApiTokens;

use Illuminate\Auth\Access\Response as AccessResponse;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Authenticatable ;
use Illuminate\Contracts\Validation\Validator as ValidationValidator;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Response;
use Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Unique;
use PhpParser\Node\Stmt\ElseIf_;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class BankController extends Controller
{
    
public function add_bank(Request $request){
    $data=Validator::make($request->all(),[
    'name'=>'required',
    'nameState'=>'required',
    'address'=>'required'      

    ]);
    if($data->fails()){
        return Response()->json([$data->errors()]);
    }
$name=$request['name'];
$address=$request['address'];
$nameState=$request['nameState'];
$state=state_model::where('nameState','=',$nameState)->first();
    $stateId=$state->id;
$location=location_model::where('state_id','=',$stateId)->where('address','=',$address)->first();
if($location){
    $locationId=$location->id;
    $bank=Bank_model::create([
        'location_id'=>$locationId,
        'name'=>$name,
        'address'=>$address
        
    ]);
return response()->json(['bank'=>$bank,'state'=>$state,'location'=>$location]);
}
else{

    $location=location_model::create([
        'address'=>$address,
        'state_id'=>$stateId
        ]);
        $bank=Bank_model::create([
            'location_id'=>$location->id,
            'name'=>$name,
            'address'=>$address
            
        ]);
        return response()->json(['bank'=>$bank,'state'=>$state,'location'=>$location]);
}
}
public function create_bank_account(Request $request){
    $data=Validator::make($request->all(),[
        'name'=>'required',
         'address'=>'required'      
       
           ]);
           if($data->fails()){
               return Response()->json([$data->errors()]);
           }
 $name=$request['name'];
 $address=$request['address'];

 $bank=Bank_model::where('name','=',$name)->where('address','=',$address)->first();
 if(!$bank){
    return response()->json(['not found bank with this data']);
 }
 $bankId=$bank->id;
 $userid=auth()->user()->id;

//  $length = 0; 
// $randomString = Str::random(); 

$randomNumber = rand(100000, 999999); 
$accountNumber = 'AC' .$randomNumber ;
$bankAccount=Account_bank::create([
    'users_id'=>$userid,
    'bank_id'=>$bankId,
    'number_account'=>$accountNumber,
    'value_of_account'=>0
]);
return response()->json(['bank account'=>$bankAccount]);
}
// public function show_my_account(){
//     $userid = auth()->user()->id;
//     $account = Account_bank::where('users_id','=',$userid)->get();
//     return Response()->json($account[0]);
// }
public function show_my_account(){
    $userid=auth()->user()->id;
    $account=Account_bank::where('users_id','=',$userid)->get();
    if(!$account->isEmpty())
    return Response()->json(['result'=>$account[0]]);

    return Response()->json(['result'=>null]);
}


public function recharge_my_account( Request $request )
{
    $data=Validator::make($request->all(),[
      'number_account'=>'required',
      'value_of_charge'=>'required'
        
    ]);        
           if($data->fails()){
               return Response()->json([$data->errors()]);
           }
           
           $userid=auth()->user()->id;
           $numberAccount=$request['number_account'];
           $valueCharge=$request['value_of_charge'];

           if(!Auth::guard('account')->attempt($request->only(['users_id'=>$userid,'number_account'=>$numberAccount]),true)){
            if(!Account_bank::where('users_id','=',$userid)->first()){
            return Response()->json(['message'=>'error  bank not found']);

            }
            if(!Account_bank::where('number_account','=',$numberAccount)->first()){
                return Response()->json(['message'=>'error  bank not found']);
    
                }
           }
        $account=Account_bank::where('users_id','=',$userid)->where('number_account','=',$numberAccount)->first();
           
           $accountNew=Account_bank::find($account->id);
           $accountNew->value_of_account+=$valueCharge;
           $accountNew->save();
        return Response()->json(['message'=>'done successfuly','new account'=>$accountNew]);

}

public function buy(Request $request){  
    //required id property , number account buyer user ,token buyer user
$userid=auth()->user()->id;
$idproperty=$request['id_property'];
$numberAccount=$request['number_account'];
$bank_account1=Account_bank::where('users_id','=',$userid)->where('number_account','=',$numberAccount)->first();
if($bank_account1){
$value_of_account1=$bank_account1->value_of_account;
}
else{
    return response()->json(['message'=>'buyer dont have any bank account']);
}

$property=property_special_model::find($idproperty);
if($property->wasSell_or_wasRented=='wasSell'){
    return response()->json(['message'=>'this property was sold so you can not buy it']);
}
if($property->rent_or_sell=='rent'){
    return response()->json(['message'=>'this property is rent  so you can not sell it']);
}
$userid2=$property->users_id;
$price=$property->price;
if($value_of_account1 < $price){
return response()->json(['message'=>'You do not have enough balance in your bank account to buy this property']);
}
$bank_account2=Account_bank::where('users_id','=',$userid2)->first();
if($bank_account2){
    $numberAccountSeller=$bank_account2->number_account;
    $value_of_account2=$bank_account2->value_of_account;

}
else{
    return response()->json(['message'=>'seller dont have any bank account']);
}


 $bank_account1->value_of_account-=$price;
 $bank_account1->save();
 $bank_account2->value_of_account+=$price;
 $bank_account2->save();
 $property->wasSell_or_wasRented='wasSell';
 $property->save();

 $sell=selles_model::create([
'users_id'=>$userid2,
'id_buyer'=>$userid,
'property_special_id'=>$idproperty
 ]);


 return response()->json(['message'=>'sell operation was done successfully',$sell]);
}


public function rent(Request $request){  
    //required id property , number account buyer user ,token buyer user
$userid=auth()->user()->id;
$idproperty=$request['id_property'];
$numberAccount=$request['number_account'];

$bank_account1=Account_bank::where('users_id','=',$userid)->where('number_account','=',$numberAccount)->first();
if($bank_account1){
$value_of_account1=$bank_account1->value_of_account;
}
else{
    return response()->json(['message'=>'buyer dont have any bank account']);
}

$property=property_special_model::find($idproperty);
if($property->wasSell_or_wasRented=='wasRented'){
    return response()->json(['message'=>'this property was rented so you cant rent this']);
}
if($property->rent_or_sell=='sell'){
    return response()->json(['message'=>'this property is sell  so you cant rent this']);
}
$userid2=$property->users_id;
$monthlyRent=$property->monthlyRent;
if($value_of_account1 < $monthlyRent){
return response()->json(['message'=>'You do not have enough balance in your bank account to rent this property']);
}
$bank_account2=Account_bank::where('users_id','=',$userid2)->first();
if($bank_account2){
    $numberAccountSeller=$bank_account2->number_account;
    $value_of_account2=$bank_account2->value_of_account;

}
else{
    return response()->json(['message'=>'renter dont have any bank account']);
}


 $bank_account1->value_of_account-=$monthlyRent;
 $bank_account1->save();
 $bank_account2->value_of_account+=$monthlyRent;
 $bank_account2->save();
 $property->wasSell_or_wasRented='wasRented';
 $property->save();

 $rent=rents_model::create([
'users_id'=>$userid2,
'id_rent_user'=>$userid,
'property_special_id'=>$idproperty
 ]);


 return response()->json(['message'=>'rent operation was done successfully',$rent]);
}


}