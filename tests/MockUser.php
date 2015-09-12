<?php

use Zoe\User;

class MockUser extends User {

    protected $subscribed;
    protected $plan;
    protected $expired;
    protected $trial_allowed;
    protected $trial_expired;

    public function setTrial_expired($trial_expired) {
        $this->trial_expired = $trial_expired;
    }

    public function setSubscribed($subscribed) {
        $this->subscribed = $subscribed;
    }

    public function setPlan($plan) {
        $this->plan = $plan;
    }

    public function setExpired($expired) {
        $this->expired = $expired;
    }

    public function subscribed() {
        return $this->subscribed;
    }

    public function getStripePlan() {
        return $this->plan;
    }

    public function expired() {
        return $this->expired;
    }

    public function setTrial_allowed($trial_allowed) {
        $this->trial_allowed = $trial_allowed;
    }

    /**
     * Get the trials for the user
     */
    public function trial() {
        return $this->hasOne('Zoe\Trial', 'user_id');
    }

    protected function getTrial($app) {
        if ($this->trial_allowed) {
            $a = $app = factory(Zoe\Application::class)->create();
            $a->name = $app;
            $tt = factory(Zoe\TrialType::class)->create();
            $trial = Zoe\Trial::makeTrial($this, $a, $tt);
            if ($this->trial_expired) {
                $trial->expires = Carbon\Carbon::now()->subDay();
            }
            return $trial;
        }
        return null;
    }

}
