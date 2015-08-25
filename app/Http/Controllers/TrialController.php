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
            $application = $this->getApplication($request);
            $trial_type = $this->getTrialType($request);

            if (isset($application) && isset($trial_type)) {

                if ($this->checkTrial($request->user(), $application,
                                $trial_type)) {
                   \Session::flash('growl', ['type' => 'danger', 'message' => 'Trial already exists!']);
                   return redirect('applications');
                }

                try {
                    $trial = Trial::makeTrial(
                            $request->user(), 
                            $application,
                            $trial_type);
                    
                    $trial->save();
                    
                    //Clear available apps cache
                    Cache::forget('user_apps_'.$request->user()->id);
                    
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
     * Utility methond to get Application object.
     * @param Request $request
     * @return Zoe\Application
     */
    private function getApplication(Request $request) {
        if ($request->has('application')) {
            $app = $request->get('application');
            return Application::getByName($app);
        } else {
            return null;
        }
    }

    /**
     * Utility method to get TrialType object
     * @param Request $request
     * @return TrialType
     */
    private function getTrialType(Request $request) {
        if ($request->has('type')) {
            $type = $request->get('type');
            return TrialType::getByName($type);
        } else {
            return null;
        }
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
