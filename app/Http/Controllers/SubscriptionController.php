<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionController extends Controller {
    /*
      |--------------------------------------------------------------------------
      |Subscription Controller
      |--------------------------------------------------------------------------
      |
      | This controller performs service subscription
      |
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the subscription screen to the user.
     *
     * @return Response
     */
    public function index() {
        return view('subscribe');
    }

    public function subscribe(Request $request) {
        if ($request->user() && $request->has('token')) {
            
            try
            {
                $request->user()->subscription('SCConverter')->create($request->input('token'));
                return response()->json(['success' => 'Thank You! '
                    . 'Your payment has been accepted!']);
            }
            catch(Exception $e)
            {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }
    
    public function show(Request $request)
    {
        if ($request->user())
        {
            $subscription = null;
            if($request->user()->subscribed())
            {
                $user = $request->user();      
                $subscription = array();
                $subscription['plan'] = $user->getStripePlan();
                $subscription['is_trial'] = $user->onTrial();
                $subscription['trial_end'] = $user->getTrialEndDate();
                $subscription['expired'] = $user->expired();
                $subscription['subscription_end'] = $user->getSubscriptionEndDate();
                $subscription['last_four'] = $user->getLastFourCardDigits();
                
                 return view('subscription',
                        ['subscription' => $subscription]);
            }
            else
            {
                 return view('subscription',
                        ['error' => 'You have no active subscriptions.']);
            }
        }
    }

}
