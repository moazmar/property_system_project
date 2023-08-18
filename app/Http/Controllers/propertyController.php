<?php

namespace App\Http\Controllers;

use App\Models\location_model;
use App\Models\property_special_model;
use App\Models\state_model;
use App\Models\favorate_model;
use App\Models\rate_property_model;
use App\Models\Bank_model;
use App\Models\Account_bank;


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

class propertyController extends Controller
{

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
            'area'=>'required|numeric|min:10'
            
        ]);
        if($validation->fails()){


    return Response()->json(['error'=>$validation->errors()]);

}
$user=User::find(auth()->user()->id);
if($user->isSuspended()){
    return response()->json(['user suspend']);
}

$namestate=$request['nameState'];
$address=$request['address'];
$state=state_model::where('nameState','=',$namestate)->first();
$stateId=$state->id;
$location1=location_model::where('state_id','=',$stateId)->where('address','=',$address)->first();

    if($location1 ){
    $locationId=$location1->id;
        
$request['users_id']=auth()->user()->id;
$avgSell=$request['price']/$request['area'];
$avgRent=$request['monthlyRent']/$request['area'];

if( $request['rent_or_sell']=="rent"){

    $property=property_special_model::create([
        'location_id'=>$locationId,
        'users_id'=> $request['users_id'],
        'typeofproperty'=>$request['typeofproperty'],
        'rent_or_sell'=>$request['rent_or_sell'],
        'address'=>$request['address'],
        'numberofRooms'=>$request['numberofRooms'],
        'bathRoom'=>$request['bathRoom'],
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
            'location_id'=>$locationId,
            'users_id'=> $request['users_id'],
            'typeofproperty'=>$request['typeofproperty'],
            'rent_or_sell'=>$request['rent_or_sell'],
            'address'=>$request['address'],
            'numberofRooms'=>$request['numberofRooms'],
        'bathRoom'=>$request['bathRoom'],

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
    return Response()->json(['state'=>$state,'location'=>$location1,'property'=>$property]);


}
else{
$location=location_model::create([

    'address'=>$address,
    'state_id'=>$stateId
    ]);

        $request['users_id']=auth()->user()->id;
    $avgSell=$request['price']/$request['area'];
    $avgRent=$request['monthlyRent']/$request['area'];

    if( $request['rent_or_sell']=="rent"){

    $property=property_special_model::create([
        'location_id'=>$location->id,
        'users_id'=> $request['users_id'],
        'typeofproperty'=>$request['typeofproperty'],
        'rent_or_sell'=>$request['rent_or_sell'],
        'address'=>$request['address'],
        'numberofRooms'=>$request['numberofRooms'],
        'bathRoom'=>$request['bathRoom'],

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
        'bathRoom'=>$request['bathRoom'],

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
    return Response()->json(['state'=>$state,'location'=>$location,'property'=>$property]);

     }
     
    }


    public function showSlider()
    {

 $propertyRent=property_special_model::
 where('rent_or_sell','=','rent')->where('wasSell_or_wasRented','=',null)->orderBy('rent_square_meter','asc')->orderBy('numberofRooms','desc')->get();

 $propertyprice=property_special_model::
 where('rent_or_sell','=','sell')->where('wasSell_or_wasRented','=',null)->orderBy('price_square_meter','asc')->orderBy('numberofRooms','desc')->get();



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

 if($property && $property->wasSell_or_wasRented==null){
 $idlocation=$property->location_id;
 $userid=$property->users_id;
 $rateSum=rate_property_model::where('users_id','=',$userid)->sum('rate');
 $countRate=rate_property_model::where('users_id','=',$userid)->count();
 if($countRate==0){
    $rate=0;
    
 $user=User::find($userid);
 $nameuser=$user->name;
 $userimage=$user->image;
 $location=location_model::find($idlocation);
 $name=$location->address;
 $stateid=$location->state_id;
 $state=state_model::find($stateid);
 $namestate=$state->nameState;

 return Response()->json(['owner name'=>$nameuser,'owner images'=>$userimage,'rate'=>$rate,'locationName'=>$name,'namestate'=>$namestate,'property'=> $property]);

 }
 $rate=$rateSum/$countRate;
 $user=User::find($userid);
 $nameuser=$user->name;
 $userimage=$user->image;
 $location=location_model::find($idlocation);
 $name=$location->address;
 $stateid=$location->state_id;
 $state=state_model::find($stateid);
 $namestate=$state->nameState;

 return Response()->json(['owner name'=>$nameuser,'owner images'=>$userimage,'rate'=>$rate,'locationName'=>$name,'namestate'=>$namestate,'property'=> $property]);
 }
 else return Response()->json([null]);


    }
    public function property(){
        // $h[]=array();
 $property=property_special_model::inRandomOrder()->get();
 if(!$property->isEmpty()){
 foreach($property as $pro){
    if($pro->wasSell_or_wasRented==null){
        $userid=$pro->users_id;
        $rateSum=rate_property_model::where('users_id','=',$userid)->sum('rate');
        $countRate=rate_property_model::where('users_id','=',$userid)->count();
     if($countRate==0){
        $rate=0;
     } else
     $rate=$rateSum/$countRate; 
    $user=User::find($userid);
    $nameuser=$user->name;
    $userimage=$user->image;
    $locationid=$pro->location_id;
    $location=location_model::find($locationid);
    $stateid=$location->state_id;
    $state=state_model::find($stateid);
    
    $h[]=array(
    "owner name"=>$nameuser,
    "owner image"=>$userimage,
    "rate"=>$rate,    
    "property"=>$pro,
    "location"=>$location,
    "state"=>$state
    
    );
    }
    else{
        // $h[]=null;
        continue;

    }
}

if( empty($h) ){
    return response()->json(null);
}else
return Response()->json($h);
}
else return null;
    }


    public function upload_image(Request $request){
        $images=array();
        if($request['image']){
      
            $files=$request->file('image');
                   foreach($files  as  $image){
                        $filename=$image->getClientOriginalName();
                        $filenameExtention= uniqid() . '.' .$image->getClientOriginalExtension();
                        $image->move('public/Image/',$filenameExtention);
                        $url=url('public/Image/',$filenameExtention);
                        array_push($images,$url);
            }
             return $images;
        }  
        else return null;          
    }


    // public function upload_image(Request $request)

    // {

    //     $images = array();

    //     if ($request->hasAny("images")) {

    //         $files = $request->get("images");

    //         foreach ($files as $filee) {

    //             $file = base64_decode($filee, true);

    //             $extension = "jpg"; // Set the default extension to 'jpg' if the original extension cannot be determined

    //             // Get the original file extension from the MIME type

    //             $finfo = finfo_open();

    //             $mime_type = finfo_buffer($finfo, $file, FILEINFO_MIME_TYPE);

    //             finfo_close($finfo);

    //             $mime_parts = explode('/', $mime_type);

    //             if (count($mime_parts) == 2) {

    //                 $extension = $mime_parts[1];

    //             }

    //             $filename = time() . rand(1, 50) . '.' . $extension;

    //             $tempPath = tempnam(sys_get_temp_dir(), 'tmp'); // Create a temporary file to store the decoded image data

    //             file_put_contents($tempPath, $file); // Write the decoded image data to the temporary file

    //             $uploadedFile = new \Illuminate\Http\UploadedFile($tempPath, $filename, mime_content_type($tempPath), null, true); // Create a new instance of UploadedFile

    //             $uploadedFile->move('public/Image/', $filename);

    //             $url = url('public/Image/' . $filename);

    //             array_push($images, $url);

    //         }

    //         return $images;

    //     } else {

    //         return null;

    //     }

    // }


    public function upload_video(Request $request){


    //     $this->validate($request, [
    //         'video' => 'required|file|mimetypes:video/mp4'

    //   ]);
      
      $video=$request->file('video');

      if ($request['video']){
        $filename=$video->getClientOriginalName();
        $filenameExtention= time(). '.' .$video->getClientOriginalExtension();
        $video->move('public/video/',$filenameExtention);
        $url=url('public/video/',$filenameExtention);
      
      return $url;
      }
      else return null;
    }
public function edit_property(Request $request){
    $userid=auth()->user()->id;
    $property=property_special_model::where('users_id','=',$userid)->where('id','=',$request['id'])->first();
    if($property){
        $property->update(Request()->all());
if($request->hasFile('image')){
    $property->image=$this->upload_image($request);
        // $property->update(Request()->all());
    $property->save();
    return response()->json(['property edit'=> $property]);
    }
    else{
        $property->update(Request()->all());
        $property->save();
        return response()->json(['property edit'=> $property]);
    }

}
    return response()->json(['dont have any property to edit']);
    
}
public function delete_property(Request $request){

    $userid=auth()->user()->id;
    $idproperty=$request['id_property'];
    $property=property_special_model::where('id','=',$idproperty)->where('users_id','=',$userid)->first();
    if($property){
        $property->delete();
    return response()->json(['delete property successfully',$property]);

    }
    return response()->json(['dont have any property']);

}

}