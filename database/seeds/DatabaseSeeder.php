<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Model::unguard();

        DB::table('applications')->insert([
            'name' => 'SC Medicaid Converter',
        ]);
        
        DB::table('trial_types')->insert([
            'name' => 'Standard 14 weeks',
            'length' => 14,
        ]);
    }

}
