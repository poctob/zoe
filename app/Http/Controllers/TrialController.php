<?php

namespace Zoe\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Zoe\Application;
use Zoe\TrialType;
use Zoe\Trial;
use \Carbon\Carbon;

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
                    $trial = $this->makeTrial($request->user(), $application,
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
     * Utility methond to get Application object.
     * @param Request $request
     * @return Zoe\Application
     */
    private function getApplication(Request $request) {
        if ($request->has('application')) {
            $app = $request->get('application');
            return $this->getApplicatonByName($app);
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
            return $this->getTrialTypeByName($type);
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
        return $this->trialExists($user->id, $application->id, $trial_type->id);
    }

    /**
     * Makes new Trial object.
     * @param User $user
     * @param Application $application
     * @param TrialType $trial_type
     * @return Trial
     */
    private function makeTrial($user, $application, $trial_type) {
        $trial = new Trial();
        $trial->application_id = $application->id;
        $trial->user_id = $user->id;
        $trial->trial_type_id = $trial_type->id;

        $now = Carbon::now();
        $length = $trial_type->length;

        if ($length > 0) {
            $trial->expires = $now->addDays($length);
        }

        return $trial;
    }

    /**
     * Utility to get application based on its name.
     * @param string $name
     * @return Zoe\Application
     */
    private function getApplicatonByName($name) {
        return Application::where('name', $name)->first();
    }

    /**
     * Utility to get trial type by name
     * @param string $name
     * @return Zoe\TrialType
     */
    private function getTrialTypeByName($name) {
        return TrialType::where('name', $name)->first();
    }

    /**
     * Checks if trial already exists for specified type and application.
     * @param int $user_id
     * @param int $application_id
     * @param int $trial_type_id
     * @return boolean
     */
    private function trialExists($user_id, $application_id, $trial_type_id) {
        $trial = Trial::where(['user_id' => $user_id, 'application_id' => $application_id,
                    'trial_type_id' => $trial_type_id]);

        return $trial != null;
    }

}
