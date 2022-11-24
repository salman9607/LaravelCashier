<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Payment;
use App\Services\InvoicesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\ChargeSuccessNotification;

class BillingController extends Controller
{
    public function index()
    {
//        $payment = Payment::with('user')->find();
//        return (new InvoicesService())->generateInvoice('$payment');

        $plans = Plan::all();
        $currentPlan = auth()->user()->subscription('default') ?? NULL;
        $paymentMethods = auth()->user()->paymentMethods();
        $defaultPaymentMethod = auth()->user()->defaultPaymentMethod();
        return view('billing.index', compact('plans', 'currentPlan', 'paymentMethods', 'defaultPaymentMethod'));
    }

    public function cancel()
    {
        auth()->user()->subscription('default')->cancel();
        return redirect()->route('billing');
    }

    public function resume()
    {
        auth()->user()->subscription('default')->resume();
        return redirect()->route('billing');
    }
}
