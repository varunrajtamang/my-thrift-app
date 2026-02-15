<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SellerSubscriptionResource;
use App\Models\SellerSubscription;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SellerSubscriptionController extends Controller
{
    /**
     * List subscriptions for the authenticated user.
     */
    public function index(Request $request)
    {
        $subscriptions = SellerSubscription::with('plan')
            ->where('user_id', $request->user()->id)
            ->latest('end_date')
            ->get();

        return SellerSubscriptionResource::collection($subscriptions);
    }

    /**
     * Lightweight status endpoint for frontend gating.
     */
    public function myStatus(Request $request)
    {
        $now = Carbon::now();

        $activeSubscription = SellerSubscription::with('plan')
            ->where('user_id', $request->user()->id)
            ->where('payment_status', 'paid')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->latest('end_date')
            ->first();

        return response()->json([
            'has_active_subscription' => (bool) $activeSubscription,
            'subscription' => $activeSubscription ? new SellerSubscriptionResource($activeSubscription) : null,
        ]);
    }

    /**
     * Purchase/create a subscription for authenticated user.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:subscription_plans,id',
            'is_auto_renew' => 'required|boolean',
            'payment_status' => 'nullable|in:paid,pending,failed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $now = Carbon::now();

        $latestActiveSubscription = SellerSubscription::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->where('end_date', '>=', $now)
            ->latest('end_date')
            ->first();

        $startDate = $latestActiveSubscription
            ? Carbon::parse($latestActiveSubscription->end_date)
            : $now;

        $endDate = match ($plan->duration_type) {
            'daily' => $startDate->copy()->addDays($plan->duration_value),
            'weekly' => $startDate->copy()->addWeeks($plan->duration_value),
            'monthly' => $startDate->copy()->addMonths($plan->duration_value),
            'yearly' => $startDate->copy()->addYears($plan->duration_value),
            default => null,
        };

        if (! $endDate) {
            return response()->json(['error' => 'Invalid duration type in plan.'], 422);
        }

        $paymentStatus = $request->input('payment_status', 'paid');

        $subscription = SellerSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_listings' => $plan->max_listings,
            'remaining_listings' => $plan->max_listings,
            'is_auto_renew' => (bool) $request->is_auto_renew,
            'payment_status' => $paymentStatus,
        ]);

        if ($paymentStatus === 'paid' && $user->user_type !== 'seller') {
            $user->update(['user_type' => 'seller']);
        }

        return new SellerSubscriptionResource($subscription->load('plan'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, SellerSubscription $sellerSubscription)
    {
        if ($sellerSubscription->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new SellerSubscriptionResource($sellerSubscription->load('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $subscription = SellerSubscription::findOrFail($id);

        if ($subscription->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:subscription_plans,id',
            'is_auto_renew' => 'required|boolean',
            'payment_status' => 'required|in:paid,pending,failed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $now = Carbon::now();
        $startDate = $now;

        $endDate = match ($plan->duration_type) {
            'daily' => $startDate->copy()->addDays($plan->duration_value),
            'weekly' => $startDate->copy()->addWeeks($plan->duration_value),
            'monthly' => $startDate->copy()->addMonths($plan->duration_value),
            'yearly' => $startDate->copy()->addYears($plan->duration_value),
            default => null,
        };

        if (! $endDate) {
            return response()->json(['error' => 'Invalid duration type in plan.'], 422);
        }

        $subscription->update([
            'plan_id' => $plan->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'max_listings' => $plan->max_listings,
            'remaining_listings' => $plan->max_listings,
            'is_auto_renew' => (bool) $request->is_auto_renew,
            'payment_status' => $request->payment_status,
        ]);

        if ($request->payment_status === 'paid' && $request->user()->user_type !== 'seller') {
            $request->user()->update(['user_type' => 'seller']);
        }

        return new SellerSubscriptionResource($subscription->fresh()->load('plan'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, SellerSubscription $sellerSubscription)
    {
        if ($sellerSubscription->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $sellerSubscription->delete();

        return response()->json(['message' => 'Subscription deleted successfully.'], 200);
    }
}
