<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaim extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('claim', function(Blueprint $table)
		{
			$table->increments('id');
                        
                        $table->string('providerReference');
                        $table->string('claimReference');
                        $table->decimal('amountBilled');
                        $table->decimal('title19Payment');
                        $table->string('STS');
                        $table->string('recipientID');
                        $table->string('recipient');
                        $table->string('edits')->nullable();                        
                        
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
		Schema::drop('claim');
	}

}
