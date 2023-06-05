<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\property_special_model;
class PropertyController extends Controller
{

    public function filters(Request $request)
    {
        $validation=Validator::make($request->all(),[

            'typeofproperty'=>'required',
            'rent_or_sell'=>'required',


        ]);
        if($validation->fails()){


            return Response()->json(['error'=>$validation->errors()]);

        }

        $query = property_special_model::query();
        $query->where('rent_or_sell',$request->input('rent_or_sell'));

        $query->where('typeofproperty', $request->input('typeofproperty'));

        if ($request->has('state')) {
            $query->whereHas('location.state', function ($query) use ($request) {
                $query->where('namestate', $request->input('state') );
            });
        }


        if ($request->has('location')) {
            $query->whereHas('location', function ($query) use ($request) {
                $query->where('address', $request->input('location') );
            });
        }

        if ($request->has('area')) {
            $min_area= $request->area-10;
            $max_area=$request->area+10;
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
        return Response()->json(['properties'=>$properties]);



    }
}
