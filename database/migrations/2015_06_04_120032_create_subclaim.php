<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubclaim extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('subclaim', function(Blueprint $table)
		{
			$table->increments('id');
                        
                        $table->string('refNumber');
                        $table->dateTime('serviceDate');
                        $table->string('procedure');
                        $table->decimal('billedAmount');
                        $table->decimal('maPayment');
                        $table->string('MOD');
                        $table->decimal('allowedCharges');
                        $table->decimal('copayAmount');
                        $table->decimal('title18Payment');
                        $table->string('STS');
                        $table->integer('claim_id')->unsigned();
                        $table->foreign('claim_id')->references('id')->on('claim');
                        
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('subclaim');
	}

}
