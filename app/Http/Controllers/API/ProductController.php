<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\SellerSubscription;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        return ProductResource::collection(Product::latest()->get());
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $seller = $request->user();

        if ($seller->user_type !== 'seller') {
            return response()->json([
                'message' => 'Create a store first to become a seller'
            ], 403);
        }

        $hasStore = Store::where('user_id', $seller->id)->exists();

        if (! $hasStore) {
            return response()->json([
                'message' => 'Create your store before creating products'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'quantity' => 'required|integer|min:0',
            'condition_id' => 'required|exists:product_conditions,id',
            'audience' => 'required|in:men,women,unisex,kids',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'status' => 'sometimes|in:active,sold,inactive,deleted',
            'is_featured' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        $product = DB::transaction(function () use ($seller, $validated) {
            $activeSubscription = SellerSubscription::where('user_id', $seller->id)
                ->where('payment_status', 'paid')
                ->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
                ->orderByDesc('end_date')
                ->lockForUpdate()
                ->first();

            if (! $activeSubscription) {
                abort(response()->json([
                    'message' => 'Active paid subscription required to create product'
                ], 403));
            }

            // Backfill counter values for older rows created before counter columns existed.
            if ($activeSubscription->max_listings === null && $activeSubscription->plan_id) {
                $activeSubscription->max_listings = $activeSubscription->plan?->max_listings;
            }
            if ($activeSubscription->remaining_listings === null) {
                $activeSubscription->remaining_listings = $activeSubscription->max_listings;
            }

            // Limited plan: consume one listing slot.
            if ($activeSubscription->remaining_listings !== null) {
                if ($activeSubscription->remaining_listings <= 0) {
                    abort(response()->json([
                        'message' => 'Your plan listing limit is reached. Please upgrade or renew your subscription.'
                    ], 403));
                }

                $activeSubscription->remaining_listings -= 1;
                $activeSubscription->save();
            } elseif ($activeSubscription->isDirty(['max_listings'])) {
                // Save possible backfill on unlimited plans too.
                $activeSubscription->save();
            }

            $payload = $validated;
            $payload['seller_id'] = $seller->id;
            $payload['status'] = $payload['status'] ?? 'active';

            return Product::create($payload);
        });

        return new ProductResource($product);
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return new ProductResource($product);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'seller_id' => 'sometimes|required|exists:users,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'sometimes|required|integer|min:0',
            'condition_id' => 'sometimes|required|exists:product_conditions,id',
            'audience' => 'sometimes|required|in:men,women,unisex,kids',
            'price' => 'sometimes|required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:255',
            'size_id' => 'nullable|exists:sizes,id',
            'color_id' => 'nullable|exists:colors,id',
            'status' => 'sometimes|required|in:active,sold,inactive,deleted',
            'is_featured' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $product->update($validator->validated());

        return new ProductResource($product);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.'], 200);
    }
}
