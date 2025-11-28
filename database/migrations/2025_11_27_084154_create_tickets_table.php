<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets', function (Blueprint $table) {
			$table->increments('id');
			$table->string('type'); // e.g., VIP, Standard
			$table->decimal('price', 10, 2);
			$table->integer('quantity')->unsigned();
			$table->integer('event_id')->unsigned();
			$table->timestamps();

			$table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tickets');
	}

}
