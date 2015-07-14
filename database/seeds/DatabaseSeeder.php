<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

                $this->call('SubClaimTableSeeder');
		$this->call('ClaimTableSeeder');
                
	}

}

class ClaimTableSeeder extends Seeder {
 
       public function run()
       {
         //delete users table records
         DB::table('claim')->delete();
       }
}

class SubClaimTableSeeder extends Seeder {
 
       public function run()
       {
         //delete users table records
         DB::table('subclaim')->delete();
       }
}
