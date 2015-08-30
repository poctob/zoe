<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cache;
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

            try {
                //Clear available apps cache
                Cache::forget('user_plan_' . $request->user()->id);

                $request->user()
                        ->subscription(config('zoe.application')['NAME'])
                        ->create($request->input('token'));
                
                $this->createLocalSubscription($request->user(), 
                        config('zoe.application')['NAME']);

                return response()->json(['success' => 'Thank You! '
                            . 'Your payment has been accepted!']);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    public function show(Request $request) {
        if ($request->user()) {

            $plan_data = $this->getSubscriptionInfoFromCache($request->user());
            $allow_trial = true;

            if (isset($plan_data['subscription']) && $plan_data['subscription']['active']) {
                return view('subscription',
                        ['subscription' => $plan_data['subscription']]);
            } else if (isset($plan_data['trial'])) {
                if ($plan_data['trial']['active']) {
                    return view('subscription',
                            ['subscription' => $plan_data['trial']]);
                } else {
                    $allow_trial = false;
                }
            }
            \Session::flash('growl',
                    ['type' => 'danger', 'message' => 'You have no active subscriptions!']);

            return view('subscribe', ['allow_trial'=> $allow_trial]);
        }
    }

    private function getSubscriptionInfoFromCache($user) {
        if (Cache::has('user_plan_' . $user->id)) {
            return Cache::get('user_plan_' . $user->id);
        } else {
            $app = config('zoe.application')['NAME'];
            $trial = $user->getAppTrial($app);
            $subscription = $user->getAppSubscription($app);

            $plan_data = array();
            $plan_data['trial'] = $trial;
            $plan_data['subscription'] = $subscription;

            Cache::put('user_plan_' . $user->id, $plan_data, 60);
            return $plan_data;
        }
    }
    
    private function createLocalSubscription($user, $application)
    {
        if(isset($user) && isset($application))
        {
            $app = Zoe\Application::getByName($application);
            if(isset($app))
            {
                $now = Carbon::now();
                $end = $now->addYear();
                
                $subscription = new Zoe\Subscription();
                $subscription->startDate = $now;
                $subscription->endDate = $end;
                
                $subscription->user()->associate($user);
                $subscription->application()->associate($app);                
                
                $subscription->save();
            }
        }
    }

}
