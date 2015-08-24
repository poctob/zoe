<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zoe\Application;

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
            
            if(count($apps) == 0)
            {
                \Session::flash('growl', ['type' => 'danger', 'message' => 'You have no active applications!']);
            }
            return view('applications',
                ['apps' => $apps]);
        }
    }
    
    private function getUserApplications($user)
    {
        $trials = $user->trials;
        $apps = array();
        $applications = Application::all();
        
        foreach($applications as $a)
        {
            if($user->onPlan($a->name))
            {
                $apps[] = $a;
            }
        }

        foreach ($trials as
                $trial) {
         
            $expired = $trial->expires > 0 && $trial->expires < Carbon::now();
            
            if(!$expired && !in_array($trial->application, $apps))
            {
                $apps[] = $trial->application;
            }
        }

        return $apps;
    }

   

}
