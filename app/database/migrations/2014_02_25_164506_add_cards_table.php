<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cards', function($table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('template_id');
			$table->string('job_title');
			$table->string('tagline');
			$table->string('company');
			$table->string('cell');
			$table->string('office');
			$table->string('email');
			$table->string('web');
			$table->string('mailing_address');
			$table->string('street_address');
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
		Schema::drop('cards');
	}

}
