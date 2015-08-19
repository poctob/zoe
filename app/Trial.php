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

}
