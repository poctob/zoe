<?php

namespace Zoe;

use Illuminate\Database\Eloquent\Model;

class UserOptions extends Model {

    /**
     * Get the user that owns the option.
     */
    public function user() {
        return $this->belongsTo('\Zoe\User', 'user_id');
    }

}
