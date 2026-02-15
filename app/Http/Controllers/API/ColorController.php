<?php

namespace App\Http\Controllers\Api;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResource;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $colors = Color::all();
        return ColorResource::collection($colors);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator= Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'hex_code' =>  'required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $exits = Color::where('name', $request->name)->where('hex_code',$request->hex_code)->exists();

        if($exits){
            return response()->json(['messgae' => 'Color already exists with the same name and color']);
        }


        $color = Color::create($validator->validated());
        return new ColorResource($color);

    }

    /**
     * Display the specified resource.
     */
    public function show(Color $color)
    {
        return new ColorResource($color);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'sometimes|required|string|max:255',
            'hex_code' => 'sometimes|required|string|size:7|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()],422);
        }

        $color->update($validator->validated());
        return new ColorResource($color);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        $color->delete();
        return response()->json(['messgae' => 'Color Deleted successfully']);
    }
}
