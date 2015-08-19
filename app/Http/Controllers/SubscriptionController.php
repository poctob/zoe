<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zoe\Trial;
use Zoe\Application;
use Zoe\TrialType;
use Carbon\Carbon;

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
                $subscription['expired'] = $user->expired();
                $subscription['subscription_end'] = $user->getSubscriptionEndDate();
                $subscription['last_four'] = $user->getLastFourCardDigits();
                
                $trials = $this->getTrials($request->user());
                
                 return view('subscription',
                        ['subscription' => $subscription, 'trials' => $trials]);
            }
            else
            {
                 return view('subscription',
                        ['error' => 'You have no active subscriptions.']);
            }
        }
    }
    
    private function getTrials($user)
    {
        $trials = $user->trials;
        $all_trials = array();
        
        foreach($trials as $trial)
        {
            $t = array();
            $t['plan'] = $trial->application->name.' - '.$trial->trialType->name;
            $t['is_trial'] = true;
            $t['trial_end'] = $trial->expires;
            $t['expired'] = $trial->expires > 0 && $trial->expires < Carbon::now();
            
            $all_trials[] = $t;
        }
        
        return $all_trials;
    }

}
