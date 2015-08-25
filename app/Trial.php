<?php namespace Zoe;

use Illuminate\Database\Eloquent\Model;

class Trial extends Model {

    /**
     * Get the user that owns the trial.
     */
    public function user()
    {
        return $this->belongsTo('\Zoe\User');
    }
    
    /**
     * Get the application that owns the trial.
     */
    public function application()
    {
        return $this->belongsTo('\Zoe\Application');
    }
    
    /**
     * Get the trial type.
     */
    public function trialType()
    {
        return $this->belongsTo('\Zoe\TrialType');
    }
    
    /**
     * Checks if trial is active
     * @return boolean
     */
    public function active()
    {
        $expired = $this->expires > 0 && $this->expires < Carbon::now();
        return !$expired;
    }
    
    /**
     * Checks if trial already exists for specified type and application.
     * @param int $user_id
     * @param int $application_id
     * @param int $trial_type_id
     * @return boolean
     */
    public static function exists($user_id, $application_id, $trial_type_id) {
        $trial = self::where([
                    'user_id' => $user_id, 
                    'application_id' => $application_id,
                    'trial_type_id' => $trial_type_id]);

        return $trial->exists();
    }
    
    /**
     * Makes new Trial object.
     * @param User $user
     * @param Application $application
     * @param TrialType $trial_type
     * @return Trial
     */
    public static function makeTrial($user, $application, $trial_type) {
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

}
