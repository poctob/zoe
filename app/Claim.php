<?php namespace Zoe;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model {

	public function subclaims()
        {
            return $this->hasMany('\Zoe\SubClaims');
        }

}
