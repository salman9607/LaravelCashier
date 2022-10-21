<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout($plan_id)
    {
        $plan = Plan::findorFail($plan_id);
        $currentPlan = auth()->user()->subscription('default')->stripe_price ?? NULL;
        if (!is_null($currentPlan) && $currentPlan != $plan->stripe_plan_id)
        {
            auth()->user()->subscription('default')->swap($plan->stripe_plan_id);
            return redirect()->route('billing');
        }
        $intent = auth()->user()->createSetupIntent();
        return view('billing.checkout', compact('plan', 'intent'));
    }

    public function processCheckout(Request $request)
    {
        $plan = Plan::findOrFail($request->input('billing_plan_id'));

        try{
            $request->user()->newSubscription('default', $plan->stripe_plan_id)
//                ->trialDays(10)
                ->create($request->input('payment-method'));
            auth()->user()->update([
                'trial_ends_at' => NULL,
                'company_name' => $request->input('company_name'),
                'address_line_1' => $request->input('address_line_1'),
                'address_line_2' => $request->input('address_line_2'),
                'country' => $request->input('country'),
                'city' => $request->input('city'),
                'postcode' => $request->input('postcode'),
            ]);
            return redirect()->route('billing')->withMessage('Subscribed Successfully');
        } catch (\Exception $e){
            return redirect()->back()->withError($e->getMessage());
        }

    }
}
