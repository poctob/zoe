<?php

namespace Zoe;

use Illuminate\Database\Eloquent\Model;

class Application extends Model {

    /**
     * Get the trials for the user
     */
    public function trials() {
        return $this->hasMany('Zoe\Trial');
    }

    public function subscriptions() {
        return $this->hasMany('Zoe\Subscription');
    }

    public function trialTypesToApplications() {
        return $this->hasMany('Zoe\TrialTypesToApplication');
    }

    /**
     * Get available trials for the application.
     * @return List of trial types or null if none.
     */
    public function getAvailableTrials() {

        $trial_types_to_application = $this->trialTypesToApplications;

        if (isset($trial_types_to_application)) {
            $trial_types = array();
            foreach ($trial_types_to_application as
                    $tta) {
                $tt = $tta->trialType;

                if (isset($tt)) {
                    $trial_types[] = $tt;
                }
            }

            return $trial_types;
        }
        return null;
    }

    /**
     * Utility to get application based on its name.
     * @param string $name
     * @return Zoe\Application
     */
    public static function getByName($name) {

        if (isset($name) && strlen($name) > 0) {
            return self::where('name', $name)->first();
        } else {
            return null;
        }
    }

}
