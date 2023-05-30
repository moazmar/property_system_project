<?php

namespace App\Http\Controllers;

use App\Models\location_model;
use App\Models\property_special_model;
use App\Models\state_model;
use Illuminate\Http\Request;

use App\Models\User;
use Laravel\Passport\HasApiTokens;

use GuzzleHttp\Psr7\Response as Psr7Response;
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

class usercontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    return User::all();

    }


    public function profile ($id){
        //way 1
//  $user=DB::select('SELECT * FROM users WHERE id=?',[$id]);

 //way2
 $user=DB::table('users')->find($id);

return Response()->json(['user'=>$user]);

    }



    public function update( ){
$update= User:: find(auth()->user()->id);
 $update->update(Request()->all());

 return Response()->json(['useredit'=>$update]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        return Response()->json(['error' => $data->errors()]);
    }
//     if($request['image']){
//         if($request->hasFile('image')){
        
//         $filenameWithExt=$request->file('image')->getClientOriginalName();
//         $filename=pathinfo($filenameWithExt,PATHINFO_FILENAME);
//         $extention=$request->file('image')->getClientOriginalExtension();
//         $filenameToStore=$filename. '-' . time() . '-' .$extention;
//         $path=$request->file('image')->storeAs('image',$filenameToStore);
             


$user=User::create([
'name'=>$request['name'],
'email'=>$request['email'],
'password'=>$request['password'],
'age'=>$request['age'],
'gender'=>$request['gender'],
'information_about'=>$request['information_about'],
'password' => Hash::make($request['password']),
'phone'=>$request['phone'],
// 'image'=>URL::asset('storage'.$path)
 'image'=> $this->upload_image($request)

]);

$token=$user->createToken('authToken')->plainTextToken;
return Response()->json(['user'=>$user,'token'=>$token]);


    
//     }



//     }
        
//     $user=User::create([
//         'name'=>$request['name'],
//         'email'=>$request['email'],
//         'password'=>$request['password'],
//         'age'=>$request['age'],
//         'gender'=>$request['gender'],
//         'information_about'=>$request['information_about'],
// 'phone'=>$request['phone'],

//         'password' => Hash::make($request['password'])
        
//         ]);
        
//         $token=$user->createToken('authToken')->plainTextToken;
//         return Response()->json(['user'=>$user,'token'=>$token]);



     }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

if(!Auth::attempt($request->only('email','password'),true)){

    if( !User::where('email','=',$email)->first()){


    return Response()->json(['message'=>'error  email resend right value','token'=>null]);

    }
    if(!User::where('password','=',$password)->first()){
        return Response()->json(['message'=>'error password resend right value','token'=>null]);


    }
else
    return Response()->json(['message'=>'error value resend right value','token'=>null]);

}

$user=User::where('email',$request['email'])->first();
$token=$user->createToken('authToken')->plainTextToken;


return Response()->json(['user'=>$user,'token'=>$token]);

    }
public function logout(){


    $token = auth()->user()->tokens;


 //   $token->delete();
 Auth::logout($token);
 return Response()->json(['massage' => 'logged out successfully  ']);

}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


     public function add_property(Request $request){
        $validation=Validator::make($request->all(),[

            // 'location_id'=>'required',
            'typeofproperty'=>'required',
            'rent_or_sell'=>'required',
            //  'users_id'=>'required',
            'address'=>'required',
            'numberofRooms'=>'required',
            // 'image'=>'required',
            // 'video'=>'required',
            // 'monthlyRent'=>'required',
            // 'price'=>'required',
            'descreption'=>'required',
            'nameState'=>'required',
            'area'=>'required'
            
        ]);
        if($validation->fails()){


    return Response()->json(['error'=>$validation->errors()]);

}
$state=state_model::create([

    'nameState'=>$request['nameState']


]);
$location=location_model::create([

    'address'=>$request['address'],
    'state_id'=>$state->id
    ]);

$request['users_id']=auth()->user()->id;
$avgSell=$request['price']/$request['area'];
$avgRent=$request['monthlyRent']/$request['area'];

if($request['image'] ||$request['video'] ){
if( $request['rent_or_sell']=="rent"){

    $property=property_special_model::create([
        'location_id'=>$location->id,
        'users_id'=> $request['users_id'],
        'typeofproperty'=>$request['typeofproperty'],
        'rent_or_sell'=>$request['rent_or_sell'],
        'address'=>$request['address'],
        'numberofRooms'=>$request['numberofRooms'],
        'descreption'=>$request['descreption'],
        'nameState'=>$request['nameState'],
        'area'=>$request['area'],
        'image'=> $this->upload_image($request),
        'video'=>$this->upload_video($request),
        'monthlyRent'=>$request['monthlyRent'],
        'price'=>null,
        'rent_square_meter'=>$avgRent
        ]);
    }
    if( $request['rent_or_sell']=="sell"){

        $property=property_special_model::create([
            'location_id'=>$location->id,
            'users_id'=> $request['users_id'],
            'typeofproperty'=>$request['typeofproperty'],
            'rent_or_sell'=>$request['rent_or_sell'],
            'address'=>$request['address'],
            'numberofRooms'=>$request['numberofRooms'],
            'descreption'=>$request['descreption'],
            'nameState'=>$request['nameState'],
            'image'=> $this->upload_image($request),
         'video'=>$this->upload_video($request),
            'price'=>$request['price'],
            'monthlyRent'=>null,
         'area'=>$request['area'],
         'price_square_meter'=>$avgSell

            ]);


    }

}
else{
    if( $request['rent_or_sell']=="rent"){

        $property=property_special_model::create([
            'location_id'=>$location->id,
            'users_id'=> $request['users_id'],
            'typeofproperty'=>$request['typeofproperty'],
            'rent_or_sell'=>$request['rent_or_sell'],
            'address'=>$request['address'],
            'numberofRooms'=>$request['numberofRooms'],
            'descreption'=>$request['descreption'],
            'nameState'=>$request['nameState'],
            'image'=> null,
            'video'=>null,
            'monthlyRent'=>$request['monthlyRent'],
            'price'=>null,
           'area'=>$request['area'],
           'rent_square_meter'=>$avgRent

            ]);
        }
        if( $request['rent_or_sell']=="sell"){
    
            $property=property_special_model::create([
                'location_id'=>$location->id,
                'users_id'=> $request['users_id'],
                'typeofproperty'=>$request['typeofproperty'],
                'rent_or_sell'=>$request['rent_or_sell'],
                'address'=>$request['address'],
                'numberofRooms'=>$request['numberofRooms'],
                'descreption'=>$request['descreption'],
                'nameState'=>$request['nameState'],
                'image'=> null,
                'video'=>null,
                'price'=>$request['price'],
                'monthlyRent'=>null,
             'area'=>$request['area'],
         'price_square_meter'=>$avgSell


                ]);
    
    
        }
}
       
     return Response()->json(['state'=>$state,'location'=>$location,'property'=>$property]);




    }






     
    public function showSlider()
    {

 $propertyRent=DB::table('property_special')->
 where('rent_or_sell','=','rent')->orderBy('rent_square_meter','asc')->orderBy('numberofRooms','desc')->get();

 $propertyprice=DB::table('property_special')->
 where('rent_or_sell','=','sell')->orderBy('price_square_meter','asc')->orderBy('numberofRooms','desc')->get();



    if(!$propertyprice->isEmpty() && !$propertyRent->isEmpty()){

return Response()->json(['property sell '=>$propertyprice,'property rent'=>$propertyRent]);

 }

 if(!$propertyprice->isEmpty() && $propertyRent->isEmpty()){

 return Response()->json(['property sell '=>$propertyprice]);
                        
 }
     
 if($propertyprice->isEmpty() && !$propertyRent->isEmpty()){

return Response()->json(['property rent'=>$propertyRent]);
                                                                                           
 } 

  if($propertyprice->isEmpty() && $propertyRent->isEmpty()){

 return Response()->json('no property to show ');
                                                                                                                                             
  }
        
    }

    public function getproperty( $id){

$property=property_special_model::find($id);

if($property){
$idlocation=$property->location_id;

$location=location_model::find($idlocation);
$name=$location->address;
$stateid=$location->state_id;
$state=state_model::find($stateid);
$namestate=$state->nameState;

return Response()->json(['locationName'=>$name,'namestate'=>$namestate,'property'=> $property]);
}
else return Response()->json([null]);


    }
    public function property(){
$property=DB::table('property_special')->inRandomOrder()->get();
if(!$property->isEmpty()){
foreach($property as $pro){
$locationid=$pro->location_id;
$location=location_model::find($locationid);
$stateid=$location->state_id;
$state=state_model::find($stateid);

$h[]=array(
"property"=>$pro,
"location"=>$location,
"state"=>$state

);

}
return Response()->json($h);
}
else return null;

    }


    public function public_search(Request $request){

        $name=$request['name'];
        $users=User::where('name','like','%'. $name.'%')->get();
       
        foreach($users as $user){

        $id=$user->id;
        $nameuser=$user->name;
        $property=property_special_model::where('users_id','=',$id)->get();

            foreach($property as $pro){
                $locationId=$pro->location_id;
                $stateId=location_model::find($locationId)->state_id;
                $state=state_model::find($stateId)->nameState;
                $location=location_model::find($locationId)->address;
            
                $h[]=array(
            "name user"=>$nameuser,
            "location property"=>$location,
            "state"=>$state

                );

            }


        }





        

        if(!$property->isEmbty()){
        return Response()->json(['property'=>$property]);    
        
        }


    }






    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function upload_image(Request $request){
        $images=array();
        if($request['image']){
      
            $files=$request->file('image');
       
                   foreach($files  as  $image){
                        $filename=$image->getClientOriginalName();
                        $filenameExtention=$image->getClientOriginalExtension();
                        $filenameWithExt= $filename .'-' . time() .'-' . $filenameExtention;
                        $path=$image->storeAs('image',$filenameWithExt,'public');
                        $url=URL::asset($path);
                       

                      array_push($images,$url);
          
            }
             return $images;
        
        }  
        else return null;          
    }

    public function upload_video(Request $request){


    //     $this->validate($request, [
    //         'video' => 'required|file|mimetypes:video/mp4'

    //   ]);
      
      $video=$request->file('video');

      if ($request['video']){
        $filenameWithExt=$video->getClientOriginalName();
        $path=$video->storeAs('video',$filenameWithExt,'public');

      
      
      return $path;
      }
      else return null;
    }
}
