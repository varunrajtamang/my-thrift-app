<?php

namespace App\Http\Controllers\Api;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SizeResource;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $size = Size::all();
        return SizeResource::collection($size);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()],422);
        }
        $size = Size::create($validator->validated());
        return new SizeResource($size);
    }

    /**
     * Display the specified resource.
     */
    public function show(Size $size)
    {

        return new SizeResource($size);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Size $size)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'description' => 'sometimes|required|string|max:255'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()],422);
        }
        $size->update($validator->validated());
        return new SizeResource($size);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        $size->delete();
        return response()->json(['message' => 'Size deleted successfully']);
    }
}
