<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subscriptions', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('user_id')->unsigned();
                        $table->integer('application_id')->unsigned();
                        $table->dateTime('startDate');
                        $table->dateTime('endDate');
                        $table->boolean('active');
			$table->timestamps();
                        
                        $table->foreign('user_id')->references('id')->on('users');
                        $table->foreign('application_id')->references('id')->on('applications');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subscriptions');
	}

}
