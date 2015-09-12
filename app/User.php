<?php

namespace Zoe;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Contracts\Billable as BillableContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, BillableContract {

    use Authenticatable,
        CanResetPassword,
        Billable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    protected $dates = ['trial_ends_at', 'subscription_ends_at'];

    /**
     * Get the trials for the user
     */
    public function trial() {
        return $this->hasOne('Zoe\Trial', 'user_id');
    }

    /**
     * Checks if user has subscription to specified app
     * @param string $app Application name.
     * @return boolean True if user has access, false otherwise.
     */
    public function canAccessApp($app) {
        $trial = $this->getTrial($app);
        if (isset($trial) && $trial->active()) {
            return true;
        } else if ($this->subscribed() && $this->getStripePlan() == $app && !$this->expired()) {
            return true;
        } else {
            return false;
        }
    }

    public function trialExpired($app) {
        $trial = $this->getTrial($app);
        if (!isset($trial)) {
            return true;
        } else if (!$trial->active()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get trial information for this app.
     * @param string $app Application name.
     * @return Array of trial properties.
     */
    public function getAppTrial($app) {
        $trial = $this->getTrial($app);
        if (isset($trial)) {

            $tr = array();
            $tr['trial'] = true;
            $tr['active'] = $trial->active();
            $tr['expires'] = $trial->expires;
            $tr['created'] = $trial->created_at;
            $tr['name'] = $trial->application->name;
            $tr['cancelled'] = false;

            return $tr;
        } else {
            return null;
        }
    }

    /**
     * Get subscription information for this app.
     * @param string $app Application name.
     * @return Array of subscription properties.
     */
    public function getAppSubscription($app) {
        if ($this->subscribed() && $this->getStripePlan() == $app) {
            $subscription = array();
            $subscription['trial'] = false;
            $subscription['name'] = $app;
            $subscription['active'] = !$this->expired();
            $subscription['cancelled'] = null !== ($this->getSubscriptionEndDate());

            return $subscription;
        } else {
            return null;
        }
    }
    
    /**
     * Checks if trial is allowed for this user/application
     * @param type $app Application
     * @return boolean True if trial is allowed/false otherwise
     */
    public function isTrialAllowed($app)
    {
        $subscribed = ($this->subscribed() && $this->getStripePlan() == $app);
        
        if(!$subscribed)
        {
            $trial = $this->getTrial($app);
            return !isset($trial);
        }
        
        return false;
    }

    /**
     * Wrapper to get a parsable trial object for user.
     * @return type
     */
    protected function getTrial($app) {
        $trial = $this->trial;
        if (isset($trial) && $trial->application->name == $app) {
            return $trial;
        } else {
            return null;
        }
    }

    /**
     * Gets fillable fields for this model.
     * @return Array with fillable fields.
     */
    public function getFillableFields() {

        $fuser = array();
        foreach ($this->getFillable() as
                $f) {
            if ($f != 'password') {
                $fuser[$f] = $this->$f;
            }
        }
        return $fuser;
    }

}
