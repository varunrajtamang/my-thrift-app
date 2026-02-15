<?php

namespace App\Http\Controllers\Api;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductImageResource;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductImageResource::collection(ProductImage::all());
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'product_id' => 'required|exists:products,id',
            'is_primary' => 'nullable|boolean',
            'image_url' => 'required|string|max:500',

        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }

        $productImage = ProductImage::create($validator->validated());

        return new ProductImageResource($productImage);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductImage $productImage)
    {
        return new ProductImageResource($productImage);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductImage $productImage)
    {
        $validator = Validator::make($request->all(),[
            'product_id' => 'sometimes|required|exists:products,id',
            'is_primary' => 'sometimes|nullable|boolean',
            'image_url' => 'sometimes|required|string|max:500',

        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()], 422);
        }

        $productImage->update($validator->validated());

        return new ProductImageResource($productImage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductImage $productImage)
    {
        $productImage->delete();
        return response()->json(['message' => 'Product Image Deleted Successfully']);
    }
}
