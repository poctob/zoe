<?php namespace Zoe;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model {

        protected $table = 'claim';
	public function subclaims()
        {
            return $this->hasMany('\Zoe\SubClaim');
        }

}
