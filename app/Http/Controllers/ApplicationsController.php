<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zoe\Application;
use Cache;

class ApplicationsController extends Controller {
    /*
      |--------------------------------------------------------------------------
      |Applications Controller
      |--------------------------------------------------------------------------
      |
      | This controller performs user applications management
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
    public function index(Request $request) {
        if ($request->user() && $request->user()->subscribed()) {

            $apps = $this->getUserApplications($request->user());

            if (count($apps) == 0) {
                \Session::flash('growl',
                        ['type' => 'danger', 'message' => 'You have no active applications!']);
            }
            return view('applications', ['apps' => $apps]);
        }
    }

    /**
     * Retrieves active user applications.
     * @param Zoe/User $user User to lookup
     * @return array of Applications
     */
    private function getUserApplications($user) {

        //First we check if we have this data in cache.  If it is not there, we
        //build it and store in cache for later retrieval.
        if (Cache::has('user_apps_'.$user->id)) {
            return Cache::get('user_apps_'.$user->id);            
        } else {
            $trials = $user->trials;
            $apps = array();
            $applications = Application::all();

            foreach ($applications as
                    $a) {
                //This can take a long time, therefore reason for cache.
                if ($user->onPlan($a->name)) {
                    $apps[] = $a;
                }
            }

            foreach ($trials as
                    $trial) {

                $expired = $trial->expires > 0 && $trial->expires < Carbon::now();

                if (!$expired && !in_array($trial->application, $apps)) {
                    $apps[] = $trial->application;
                }
            }
            Cache::put('user_apps_'.$user->id, $apps, 60);
            return $apps;
        }
    }
}
