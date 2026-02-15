<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // List all categories
    public function index()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    // Show a single category
    public function show(Category $category)
    {
        $category->load('children'); // assuming 'children' is defined as a relationship
        return new CategoryResource($category);
    }

    // Store a new category (admin only)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'required|string|max:255',
            'is_active'   => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create([
            'name'        => $request->name,
            'parent_id'   => $request->parent_id,
            'description' => $request->description,
            'is_active'   => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        return (new CategoryResource($category))->additional([
            'message' => 'Category created successfully'
        ]);
    }

    // Update an existing category
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'sometimes|required|string|max:255',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'sometimes|required|string|max:255',
            'is_active'   => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update([
            'name'        => $request->name ?? $category->name,
            'parent_id'   => $request->parent_id ?? $category->parent_id,
            'description' => $request->description ?? $category->description,
            'is_active'   => $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : $category->is_active,
        ]);

        return (new CategoryResource($category))->additional([
            'message' => 'Category updated successfully'
        ]);
    }

    // Delete a category
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}

