<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
                //Clear available apps cache
                Cache::forget('user_plan_' . $request->user()->id);

                $request->user()
                        ->subscription(config('zoe.application')['NAME'])
                        ->create($request->input('token'));

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

            if (isset($plan_data['subscription'])) {
                return view('subscription', ['subscription' => $plan_data['subscription']]);
            } else if (isset($plan_data['trial'])) {
                return view('subscription', ['subscription' => $plan_data['trial']]);
            }
            \Session::flash('growl',
                    ['type' => 'danger', 'message' => 'You have no active subscriptions!']);

            return view('subscribe');
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

}
