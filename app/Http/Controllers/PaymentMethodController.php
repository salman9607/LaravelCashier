<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Mockery\Exception;

class PaymentMethodController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $intent = auth()->user()->createSetupIntent();
        return view('payment-method.create', compact('intent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            auth()->user()->addPaymentMethod($request->input('payment-method'));
            if ($request->input('default') === 1) {
                auth()->user()->updateDefaultPaymentMethod($request->input('payment-method'));
            }
        }catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
        return redirect()->route('billing')->withMessage('Payment method added successfully');
    }

    public function markDefault(Request $request, $paymentMethod)
    {
        try {
            auth()->user()->updateDefaultPaymentMethod($paymentMethod);
        }catch (Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }
        return redirect()->route('billing')->withMessage('Payment method updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
