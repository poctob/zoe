<?php

namespace Zoe;

use Illuminate\Database\Eloquent\Model;

class TrialType extends Model {

    /**
     * Get the trials for the user
     */
    public function trials() {
        return $this->hasMany('Zoe\Trial');
    }

    public function trialTypesToApplications() {
        return $this->hasMany('Zoe\TrialTypesToApplication');
    }

    /**
     * Utility to get trial type by name
     * @param string $name
     * @return Zoe\TrialType
     */
    public static function getByName($name) {
        return self::where('name', $name)->first();
    }

}
