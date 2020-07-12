<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Payment\PaymentInterface as PaymentInterface;
use App\Repositories\SubscribePlan\SubscribePlanInterface as SubscribePlanInterface;

class PaymentController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PaymentInterface $payment,SubscribePlanInterface $subscribePlan)
    {
        $this->payment = $payment;
        $this->subscribePlan = $subscribePlan;
        $this->middleware('user', ['only' => ['showPaymentOptions', 'paymentSubscribePlan']]);
    }      

    /**
     * Show Payment Options.
     *
     * @return \Illuminate\Http\Response
     */
    public function showPaymentOptions(Request $request)
    {   
        $value = $request->all();
        $mainModules = $this->subscribePlan->getActivatedMainModules();
        $subscribePlans = $this->payment->getSubscribePlans($value);
        return view('payment.option',compact('subscribePlans','mainModules'));
    }

    /**
     * payment subscribe plan.
     *
     * @return \Illuminate\Http\Response
     */
    public function paymentSubscribePlan(Request $request)
    {   
        $value = $request->all();
        $msg = $this->payment->store($value);
        return redirect()->back()->with('paymentSuccess', 'Your ' . $msg->plan_name . ' plan has been activated successfully.');
    }    
}
