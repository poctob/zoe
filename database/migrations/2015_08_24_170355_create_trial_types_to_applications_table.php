<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrialTypesToApplicationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('trial_types_to_applications',
                function(Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->unsigned();
            $table->integer('trial_type_id')->unsigned();
            $table->boolean('active')->default('1');
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications');
            $table->foreign('trial_type_id')->references('id')->on('trial_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('trial_types_to_applications');
    }

}
