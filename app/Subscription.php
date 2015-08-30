<?php namespace Zoe;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model {

	/**
     * Get the application that owns the trial.
     */
    public function application()
    {
        return $this->belongsTo('\Zoe\Application');
    }
    
    /**
     * Get the application that owns the trial.
     */
    public function user()
    {
        return $this->belongsTo('\Zoe\User');
    }

}
