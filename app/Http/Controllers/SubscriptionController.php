<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zoe\Application;
use Carbon\Carbon;
use Cache;

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

            try {
                Cache::forget('user_apps_'.$request->user()->id); 
                $request->user()->subscription('SCConverter')->create($request->input('token'));
                return response()->json(['success' => 'Thank You! '
                            . 'Your payment has been accepted!']);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    public function show(Request $request) {
        if ($request->user()) {
            $subscription = null;
            if ($request->user()->subscribed()) {
                $user = $request->user();
                $subscription = array();
                $subscription['plan'] = $user->getStripePlan();
                $subscription['expired'] = $user->expired();
                $subscription['subscription_end'] = $user->getSubscriptionEndDate();
                $subscription['last_four'] = $user->getLastFourCardDigits();

                $trials = $this->getTrials($request->user());

                $untried = $this->getUntriedApplications($request->user());
                
                $unt = $this->getUntriedWithTrialTypes($untried);

                return view('subscription',
                        ['subscription' => $subscription, 'trials' => $trials,
                    'untried' => $unt]);
            } else {
                return view('subscription',
                        ['error' => 'You have no active subscriptions.']);
            }
        }
    }

    private function getTrials($user) {
        $trials = $user->trials;
        $all_trials = array();

        foreach ($trials as
                $trial) {
            $t = array();
            $t['plan'] = $trial->application->name . ' - ' . $trial->trialType->name;
            $t['is_trial'] = true;
            $t['trial_end'] = $trial->expires;
            $t['expired'] = $trial->expires > 0 && $trial->expires < Carbon::now();

            $all_trials[] = $t;
        }

        return $all_trials;
    }

    private function getUntriedApplications($user) {
        $trials = $user->trials;
        $apps = array();

        foreach ($trials as $trial) {
          $apps[] = $trial->application->id;
        }
        
        $applications = Application::whereNotIn('id', $apps)->get();

        return $applications;
    }
    
    private function getUntriedWithTrialTypes($apps)
    {
        if(isset($apps))
        {
            $apps_and_types = array();
            foreach ($apps as $app)
            {
                $item = array();
                $item['app'] = $app;
                $item['trial_type'] = $this->getAvailableTrials($app);
                
                $apps_and_types[] = $item;
            }
            
            return $apps_and_types;
        }
    }
    
    private function getAvailableTrials(Application $application)
    {
        if(isset($application))
            $trial_types_to_application = $application->trialTypesToApplications;
        
        if(isset($trial_types_to_application))
        {
            $trial_types = array();
            foreach($trial_types_to_application as $tta)
            {
                $tt = $tta->trialType;
                
                if(isset($tt))
                {
                    $trial_types[] = $tt;
                }
            }
            
            return $trial_types;
        }
        return null;
        
    }

}
