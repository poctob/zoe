<?php

namespace Zoe;

use Illuminate\Database\Eloquent\Model;

class Application extends Model {

    /**
     * Get the trials for the user
     */
    public function trials() {
        return $this->hasMany('Trial');
    }

}
