<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('booking_id')->unsigned();
			$table->decimal('amount', 10, 2);
			$table->enum('status', ['success', 'failed', 'refunded'])->default('success');
			$table->timestamps();

			$table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payments');
	}

}
