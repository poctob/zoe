<?php namespace Zoe;

use Illuminate\Database\Eloquent\Model;

class SubClaim extends Model {

        protected $table = 'subclaim';
	public function claim()
        {
            return $this->belongsTo('\Zoe\Claim');
        }

}
