<?php

namespace App\Http\Controllers;

use App\Models\location_model;
use App\Models\property_special_model;
use App\Models\state_model;
use App\Models\favorate_model;
use App\Models\rate_property_model;
use App\Models\Bank_model;
use App\Models\Account_bank;
use App\Models\Admin_model;
use App\Models\inform_model;
use Carbon\Carbon;
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

class adminController extends Controller{

    public function Rigester(Request $request)
    {
    $data= Validator::make($request->all(),[
        'name' =>'required',
        'phone'=>'required|unique:users',
        'email'=>'required|email|unique:users',
        'password' =>'required',
        'age'=>'required',
        'gender'=>'required',
        'information_about'=>'required'

    ]);
    if ($data->fails()) {
        return Response()->json($data->errors());
    }

$admin=Admin_model::create([
'name'=>$request['name'],
'email'=>$request['email'],
'age'=>$request['age'],
'gender'=>$request['gender'],
'information_about'=>$request['information_about'],
'password' => Hash::make($request['password']),
'phone'=>$request['phone'],

 'image'=> $this->upload_image($request)

]);

$token=$admin->createToken('authToken')->plainTextToken;
return Response()->json(['admin'=>$admin,'token'=>$token]);

     }

     public function login(Request $request)
     {
         $validation=Validator::make($request->all(),[
        'email'=>'required|email',
        'password'=>'required|alphaNum'
 
 
         ]);
         if($validation->fails()){
 
             return Response()->json(['error'=>$validation->errors()]);
         }
         $email=$request['email'];
         $password=$request['password'];
 
 if(!Auth::guard('Admin')->attempt($request->only('email','password'),true)){
 
     if( !Admin_model::where('email','=',$email)->first()){
 
 
     return Response()->json(['message'=>'error  email resend right value','token'=>null]);
 
     }
     if(!Admin_model::where('password','=',$password)->first()){
         return Response()->json(['message'=>'error password resend right value','token'=>null]);
 
 
     }
 else
     return Response()->json(['message'=>'error value resend right value','token'=>null]);
 
 }
 $admin=Admin_model::where('email',$request['email'])->first();
 $token=$admin->createToken('authToken')->plainTextToken;
 return Response()->json(['admin'=>$admin,'token'=>$token]);
     }
     
     public function logout_admin(Request $request){
        $accessToken = $request->bearerToken();
            
        // Get access token from database
        $token = PersonalAccessToken::findToken($accessToken);
        
        // Revoke token
        $token->delete();
        return Response()->json(['massage' => 'logged out successfully  ']);
        
        }

     public function inform(Request $request){
        $type_of_informing=$request['type_informing'];
        $iduser=auth()->user()->id;
        if($request['user_who_isinformed_about_Him']){

            $inform=inform_model::create([
                'users_id'=>$iduser,
                'admin_id'=>$request['admin_id'],
                'property_special_id'=>null,
                'user_who_isinformed_about_Him'=>$request['user_who_isinformed_about_Him'],
                'type_of_informing'=>$request['type_of_informing']
            ]);
            return response()->json(["inform"=>$inform]);
        }

        if($request['property_special_id']){
            
            $inform=inform_model::create([
                'users_id'=>$iduser,
                'admin_id'=>$request['admin_id'],
                'property_special_id'=>$request['property_special_id'],
                'user_who_isinformed_about_Him'=>null,
                'type_of_informing'=>$request['type_of_informing']
            ]);
            return response()->json(["inform"=>$inform]);

        }

     }








    public function upload_image(Request $request){
        $images=array();
        if($request['image']){
      
            $files=$request->file('image');
       
                   foreach($files  as  $image){
                        // $filename=$image->getClientOriginalName();
                        $filenameExtention= uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move('public/Image/',$filenameExtention);
                        $url=url('public/Image/',$filenameExtention);

                      array_push($images,$url);
            }
             return $images;
        }  
        else return null;          
    }


    public function suspend(Request $request)
    {
        $id=$request['id'];
        $duration=$request['duration'];
        $user = User::findOrFail($id);
        
        // Set the suspension details
        $user->suspended_at = Carbon::now();
        $user->suspension_duration = $duration;
        $user->save();
    
        // Handle any additional suspension actions (e.g., logging out the user)
    
        return response()->json(['user suspend  successfully']);
    }

    public function unsuspend(Request $request)
    {
        $id=$request['id'];
        $user = User::findOrFail($id);
    
        // Remove the suspension details
        $user->suspended_at = null;
        $user->suspension_duration = null;
        $user->save();
    
        // Handle any additional unsuspension actions
    
        return response()->json(['user unsuspend  successfully']);
        
    }
}