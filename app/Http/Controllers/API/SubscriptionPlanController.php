<?php

namespace App\Http\Controllers\Api;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionPlanResource;
use Illuminate\Support\Facades\Validator;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        return SubscriptionPlanResource::collection(SubscriptionPlan::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'duration_type' => 'required|in:daily,weekly,monthly,yearly',
            'duration_value' => 'required|integer|min:1',
            'max_listings' => 'nullable|integer|min:0',
            'max_images_per_listing' => 'required|integer|min:0',
            'featured_listings' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $plan = SubscriptionPlan::create($validator->validated());

        return new SubscriptionPlanResource($plan);
    }

    public function show($id)
    {
        $plan = SubscriptionPlan::find($id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }
        return new SubscriptionPlanResource($plan);
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::find($id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:50',
            'price' => 'sometimes|required|numeric|min:0',
            'duration_type' => 'sometimes|required|in:daily,weekly,monthly,yearly',
            'duration_value' => 'sometimes|required|integer|min:1',
            'max_listings' => 'nullable|sometime|integer|min:0',
            'max_images_per_listing' => 'sometimes|required|integer|min:0',
            'featured_listings' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $plan->update($validator->validated());

        return new SubscriptionPlanResource($plan);
    }

    public function destroy($id)
    {
        $plan = SubscriptionPlan::find($id);
        if (!$plan) {
            return response()->json(['message' => 'Plan not found'], 404);
        }

        $plan->delete();

        return response()->json(['message' => 'Subscription plan deleted']);
    }
}
