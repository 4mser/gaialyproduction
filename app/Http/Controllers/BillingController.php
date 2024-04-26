<?php

namespace App\Http\Controllers;

use App\Models\BillingPlan;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index()
    {
        if (request()->has('res') && in_array(request()->get('res'), ['success', 'failed']))
            return view('billing.' . request()->get('res'));

        $plans = BillingPlan::orderBy('position')->get();
        return view('billing.index', compact('plans'));
    }

    public function form($plan_code)
    {
        if ($plan_code == 'basic') {
            $planId = 0;
            $planName = 'Basic';
            $price = 10;;
            $credits = env('PRICE_PER_CREDIT') * $price;
        } else {
            $plan = BillingPlan::where('code', $plan_code)->firstOrFail();
            $planId = $plan->id;
            $planName = $plan->title;
            $credits = round($plan->credits, 0);
            $price = round($plan->price, 0);
        }
        $intent = auth()->user()->createSetupIntent();
        return view('billing.form', compact('planId', 'planName', 'credits', 'price', 'intent'));
    }

    public function checkout(Request $request)
    {
        $plans = BillingPlan::orderBy('position')->pluck('id')->toArray();
        $plans[] = 0;
        $rules = [
            'billing_plan_id' => 'required|numeric|in:' . implode(',', $plans),
        ];
        $this->validate($request, $rules);
        $inputs = $request->only(array_keys($rules));

        $rules = array_merge($rules, [
            'name_on_card' => 'required|min:3|max:100',
            'payment_method' => 'required',
        ]);

        if ($inputs['billing_plan_id'] == 0) {
            $rules = array_merge($rules, [
                'credits' => 'required|numeric|regex:/^\d+$/',
                'price' => 'required|numeric',
            ]);
        }

        $this->validate($request, $rules);

        try {
            DB::beginTransaction();
            $inputs = $request->only(array_keys($rules));
            if ($inputs['billing_plan_id'] == 0) {
                $inputs['credits'] = $inputs['price'] * env('PRICE_PER_CREDIT');
            } else {
                $plan = BillingPlan::findOrFail($inputs['billing_plan_id']);
                $inputs['price'] = (int) $plan->price;
                $inputs['credits'] = $plan->credits;
            }

            $description = 'Purchase of ' . $inputs['credits'] . ' credits for $' . $inputs['price'];
            $user = auth()->user();
            $user->createOrGetStripeCustomer();

            $price = $inputs['price'] * 100;

            $paymentMethod = $user->addPaymentMethod($request->payment_method);
            $user->charge($price, $paymentMethod->id, [
                'description' => $description
            ]);

            Transaction::in([
                'user' => auth()->user(),
                'credit' => $inputs['credits'],
                'description' => $description
            ]);
            DB::commit();
            return redirect(route('billing.index') . '?res=success')->with('success', 'Payment successful!');
        } catch (Exception $ex) {
            DB::rollback();
            return redirect(route('billing.index') . '?res=failed')->with('error', 'Error trying to process payment.');
        }
    }
}
