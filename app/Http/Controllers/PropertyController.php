<?php

namespace App\Http\Controllers;

use App\Models\location_model;
use App\Models\state_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Models\property_special_model;
class PropertyController extends Controller
{


    public function add_property(Request $request)
    {
        $validation = Validator::make($request->all(), [

            // 'location_id'=>'required',
            'typeofproperty' => 'required',
            'rent_or_sell' => 'required',
            //  'users_id'=>'required',
            'address' => 'required',
            //'numberofRooms'=>'required',
            // 'image'=>'required',
            'images' => ['present', 'array'],
            'image.*' => ['image|mimes:jpeg,png,jpg,gif,svg|max:2048'],
            'video' => 'required|file|mimetypes:video/mp4',
            // 'video'=>'required',
            // 'monthlyRent'=>'required',
            // 'price'=>'required',
            'descreption' => 'required',
            'nameState' => 'required',
            'area' => 'required'

        ]);
        if ($validation->fails()) {


            return Response()->json(['error' => $validation->errors()]);

        }
        $state = new state_model();
        $state->nameState = $request['nameState'];
        $state->save();
//        $state=state_model::create([
//
//            'nameState'=>$request['nameState']
//
//
//        ]);
        $location = new location_model();
        $location->address = $request['address'];
        $location->state_id = $state->id;
        $location->save();
//        $location=location_model::create([
//
//            'address'=>$request['address'],
//            'state_id'=>$state->id
//        ]);

        $request['users_id'] = auth()->user()->id;
        $avgSell = $request['price'] / $request['area'];
        $avgRent = $request['monthlyRent'] / $request['area'];

        if ($request['images'] || $request['video']) {
            if ($request['rent_or_sell'] == "rent") {

                $property = property_special_model::create([
                    'location_id' => $location->id,
                    'users_id' => $request['users_id'],
                    'typeofproperty' => $request['typeofproperty'],
                    'rent_or_sell' => $request['rent_or_sell'],
                    'address' => $request['address'],
                    'numberofRooms' => $request['numberofRooms'],
                    'descreption' => $request['descreption'],
                    'nameState' => $request['nameState'],
                    'area' => $request['area'],
                    'image' => $this->upload_image($request),
                    'video' => $this->upload_video($request),
                    'monthlyRent' => $request['monthlyRent'],
                    'price' => null,
                    'rent_square_meter' => $avgRent
                ]);

            }
            if ($request['rent_or_sell'] == "sell") {

                $property = property_special_model::create([
                    'location_id' => $location->id,
                    'users_id' => $request['users_id'],
                    'typeofproperty' => $request['typeofproperty'],
                    'rent_or_sell' => $request['rent_or_sell'],
                    'address' => $request['address'],
                    'numberofRooms' => $request['numberofRooms'],
                    'descreption' => $request['descreption'],
                    'nameState' => $request['nameState'],
                    'image' => $this->upload_image($request),
                    'video' => $this->upload_video($request),
                    'price' => $request['price'],
                    'monthlyRent' => null,
                    'area' => $request['area'],
                    'price_square_meter' => $avgSell

                ]);


            }

        } else {
            if ($request['rent_or_sell'] == "rent") {

                $property = property_special_model::create([
                    'location_id' => $location->id,
                    'users_id' => $request['users_id'],
                    'typeofproperty' => $request['typeofproperty'],
                    'rent_or_sell' => $request['rent_or_sell'],
                    'address' => $request['address'],
                    'numberofRooms' => $request['numberofRooms'],
                    'descreption' => $request['descreption'],
                    'nameState' => $request['nameState'],
                    'image' => null,
                    'video' => null,
                    'monthlyRent' => $request['monthlyRent'],
                    'price' => null,
                    'area' => $request['area'],
                    'rent_square_meter' => $avgRent

                ]);
            }
            if ($request['rent_or_sell'] == "sell") {

                $property = property_special_model::create([
                    'location_id' => $location->id,
                    'users_id' => $request['users_id'],
                    'typeofproperty' => $request['typeofproperty'],
                    'rent_or_sell' => $request['rent_or_sell'],
                    'address' => $request['address'],
                    'numberofRooms' => $request['numberofRooms'],
                    'descreption' => $request['descreption'],
                    'nameState' => $request['nameState'],
                    'image' => null,
                    'video' => null,
                    'price' => $request['price'],
                    'monthlyRent' => null,
                    'area' => $request['area'],
                    'price_square_meter' => $avgSell


                ]);


            }
        }

        return Response()->json(['state' => $state, 'location' => $location, 'property' => $property,]);


    }

//   public function upload_image(Request $request){
//        $images=array();
//        if($request['image']){
//
//            $files=$request->file('image');
//
//                   foreach($files  as  $image){
//                        $filename=$image->getClientOriginalName();
//                        $filenameExtention=$image->getClientOriginalExtension();
//                        $filename=pathinfo($filename,PATHINFO_FILENAME);
//                        $filenameWithExt= $filename .'-' . time() .'.' . $filenameExtention;
//                        $path=$image->storeAs('image',$filenameWithExt,'public');
//                        $url=URL::asset($path);
//
//
//                      array_push($images,$url);
//
//            }
//             return $images;
//
//        }
//        else return null;
//    }
    public function upload_image(Request $request)
    {

        $images = array();
        if ($request->hasFile("images")) {
            $files = $request->file("images");
            foreach ($files as $file) {


                $filename = time() . rand(1, 50) . '.' . $file->getClientOriginalExtension();
                $file->move('public/Image/', $filename);

                $url = url('public/Image/' . $filename);
                array_push($images, $url);

            }
            return $images;

        } else return null;
    }

//
//    public function upload_video(Request $request){
//
//
//        //     $this->validate($request, [
//        //         'video' => 'required|file|mimetypes:video/mp4'
//
//        //   ]);
//
//        $video=$request->file('video');
//
//        if ($request['video']){
//            $filenameWithExt=$video->getClientOriginalName();
//            $path=$video->storeAs('video',$filenameWithExt,'public');
//
//
//
//            return $path;
//        }
//        else return null;
//    }

    public function upload_video(Request $request)
    {


//        $this->validate($request, [
//            'video' => 'required|file|mimetypes:video/mp4',
//        ]);
        if ($request->hasFile("video")) {
            $file=$request->file('video');
            $fileName = $request->video->getClientOriginalName();
           // $filePath = 'videos/' . $fileName;
            $file->move('public/video/', $fileName);

            $url = url('public/video/' . $fileName);

            //$isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));

            // File URL to access the video in frontend
            //$url = Storage::disk('public')->url($filePath);
            return $url;


        } else return null;
    }


    public function showSlider()
{

    $propertyRent = DB::table('property_special')->
    where('rent_or_sell', '=', 'rent')->orderBy('rent_square_meter', 'asc')->orderBy('numberofRooms', 'desc')->get();

    $propertyprice = DB::table('property_special')->
    where('rent_or_sell', '=', 'sell')->orderBy('price_square_meter', 'asc')->orderBy('numberofRooms', 'desc')->get();


    if (!$propertyprice->isEmpty() && !$propertyRent->isEmpty()) {

        return Response()->json(['property sell ' => $propertyprice, 'property rent' => $propertyRent]);

    }

    if (!$propertyprice->isEmpty() && $propertyRent->isEmpty()) {

        return Response()->json(['property sell ' => $propertyprice]);

    }

    if ($propertyprice->isEmpty() && !$propertyRent->isEmpty()) {

        return Response()->json(['property rent' => $propertyRent]);

    }

    if ($propertyprice->isEmpty() && $propertyRent->isEmpty()) {

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
    public function property()
{
    $property = DB::table('property_special')->inRandomOrder()->get();
    if (!$property->isEmpty()) {
        foreach ($property as $pro) {
            $locationid = $pro->location_id;
            $location = location_model::find($locationid);
            $stateid = $location->state_id;
            $state = state_model::find($stateid);

            $h[] = array(
                "property" => $pro,
                "location" => $location,
                "state" => $state

            );

        }
        return Response()->json($h);
    } else return null;
}




    public function filters(Request $request)
{
    $validation = Validator::make($request->all(), [

        'typeofproperty' => 'required',
        'rent_or_sell' => 'required',


    ]);
    if ($validation->fails()) {


        return Response()->json(['error' => $validation->errors()]);

    }

    $query = property_special_model::query();
    $query->where('rent_or_sell', $request->input('rent_or_sell'));

    $query->where('typeofproperty', $request->input('typeofproperty'));

    if ($request->has('state')) {
        $query->whereHas('location.state', function ($query) use ($request) {
            $query->where('namestate', $request->input('state'));
        });
    }


    if ($request->has('location')) {
        $query->whereHas('location', function ($query) use ($request) {
            $query->where('address', $request->input('location'));
        });
    }

    if ($request->has('area')) {
        $min_area = $request->area - 10;
        $max_area = $request->area + 10;
        $query->whereBetween('area', [$min_area, $max_area]);
    }

    if ($request->has('num_of_rooms')) {
        $query->where('numberofRooms', $request->input('num_of_rooms'));
    }

    if ($request->has('price')) {
        $query->where('price', $request->input('price'));
    }

    if ($request->has('monthlyRent')) {
        $query->where('monthlyRent', $request->input('monthlyRent'));
    }


    $properties = $query->orderBy('price', 'asc')
        ->get();
    return Response()->json(['properties' => $properties]);

   }


}
