<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Cache;
use Carbon\Carbon;
use Zoe\Application;
use Zoe\Subscription;
use Zoe\Http\Controllers\MailController as Mailer;

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

    /**
     * Creates new subscription.  Here is what we need to get done:
     * 
     * 1. Check if we have a valid user and valid CC token.
     * 2. Clear user's subscription cache.
     * 3. Create a new Stripe subscription for this user.
     * 4. Update subscription data locally.
     * 5. Update local trial data so it can't be used again.
     * 6. Return an appropriate response.
     * @param Request $request
     * @return Json response.
     */
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

                \Session::flash('growl',
                        ['type' => 'success',
                    'message' => 'Your subscription has been created!']);

                return $this->show($request);
            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    /**
     * Cancels user's subscription
     * @param Request $request
     * @return Subscribe view if successfuly, subscription view otherwise.
     */
    public function cancel(Request $request) {
        if ($request->user()) {
            $app = config('zoe.application')['NAME'];
            $subscription = $request->user()->getAppSubscription($app);
            if (isset($subscription) && $subscription['active']) {
                //Clear available apps cache
                Cache::forget('user_plan_' . $request->user()->id);

                $request->user()->subscription()->cancel();

                \Session::flash('growl',
                        ['type' => 'warning',
                    'message' => 'Your subscription has been cancelled!']);

                return view('subscribe', ['allow_trial' => false]);
            }
        }
        \Session::flash('growl',
                ['type' => 'error', 'message' => 'Invalid subscription parameters!']);
        return $this->show($request);
    }

    /**
     * Displays current subscription to user.
     * @param Request $request
     * @return Subscription view, if exists, Subscribe view otherwise
     */
    public function show(Request $request) {
        if ($request->user()) {

            $plan_data = $this->getSubscriptionInfoFromCache($request->user());
            $allow_trial = true;
            $sub = $plan_data['subscription'];
            $tr = $plan_data['trial'];

            if (isset($sub)) {
                if ($sub['cancelled']) {
                    $allow_trial = false;
                }
                if ($sub['active']) {
                    return view('subscription', ['subscription' => $sub]);
                }
            } else if ($allow_trial && isset($tr)) {
                if ($tr['active']) {
                    return view('subscription', ['subscription' => $tr]);
                } else {
                    
                }
            }
            \Session::flash('growl',
                    ['type' => 'danger', 'message' => 'You have no active subscriptions!']);

            return view('subscribe', ['allow_trial' => $allow_trial]);
        }
    }

    /**
     * Fetches subscription info from local cache.
     * @param User $user
     * @return array user subscription plan
     */
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

    /**
     * Creates local subscription to keep track of expiration dates.
     * @param User $user
     * @param String $application application name
     */
    private function createLocalSubscription($user, $application) {
        if (isset($user) && isset($application)) {
            $app = Application::getByName($application);
            if (isset($app)) {
                $now = Carbon::now();
                $end = $now->addYear();

                $subscription = new Subscription;
                $subscription->startDate = $now;
                $subscription->endDate = $end;

                $subscription->user()->associate($user);
                $subscription->application()->associate($app);

                $subscription->save();

                $mailer = new Mailer();
                $mailer->sendSubscriptionStartEmail($request->user(),
                        $end);
            }
        }
    }

}
