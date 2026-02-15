<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductConditionResource;


use App\Models\ProductCondition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductConditionResource::collection(ProductCondition::all());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:50',
            'description' => 'nullable|max:255',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()],422);
        }

        $productCondition = ProductCondition::create($validator->validated());

        return new ProductConditionResource($productCondition);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductCondition $productCondition)
    {
        return new ProductConditionResource($productCondition);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductCondition $productCondition)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'sometimes|required|string|max:50',
            'description' => 'sometimes|required|string|max:255',
        ]);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()],422);
        }

        $productCondition->update($validator->validated());

        return new ProductConditionResource($productCondition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductCondition $productCondition)
    {
        $productCondition->delete();
        return response()->json(['message' => 'Product Conditions row successfully deleted']);
    }
}
