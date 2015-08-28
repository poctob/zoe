<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zoe\Application;
use Zoe\TrialType;
use Zoe\Trial;
use Cache;

class TrialController extends Controller {
    /*
      |--------------------------------------------------------------------------
      |Trial Controller
      |--------------------------------------------------------------------------
      |
      | This controller performs user trial management
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
     * Adds subscription to the database.
     *
     * @return Response
     */
    public function create(Request $request) {
        \Session::reflash();
        if ($request->user()) {
            $app = config('zoe.application')['NAME'];
            $tt = config('zoe.application')['TRIAL_TYPE'];
            
            $application = Application::getByName($app);
            $trial_type = TrialType::getByName($tt);

            if (isset($application) && isset($trial_type)) {

                if ($this->checkTrial($request->user(), $application,
                                $trial_type)) {
                   \Session::flash('growl', ['type' => 'danger', 'message' => 'Trial already exists!']);
                   return redirect('applications');
                }

                try {
                    //Clear available apps cache
                    Cache::forget('user_plan_'.$request->user()->id);
                    
                    $trial = Trial::makeTrial(
                            $request->user(), 
                            $application,
                            $trial_type);
                    
                    $trial->save();                                        
                    
                    \Session::flash('growl', ['type' => 'success', 'message' => 'Trial created successfully!']);
                    return redirect('applications');
                                        
                } catch (Exception $e) {
                    \Session::flash('growl', ['type' => 'danger', 'message' => $e->getMessage()]);
                    return redirect('applications');
                }
            }
            \Session::flash('growl', ['type' => 'danger', 'message' => 'Invalid data received!']);
            return redirect('applications');
        }
        \Session::flash('growl', ['type' => 'danger', 'message' => 'Forbidden!']);        
        return redirect('applications');
    }

    /**
     * Wrapper for trialExists method
     * @param User $user
     * @param Application $application
     * @param TrialType $trial_type
     * @return type
     */
    private function checkTrial($user, $application, $trial_type) {
        return Trial::exists($user->id, $application->id, $trial_type->id);
    }
}
