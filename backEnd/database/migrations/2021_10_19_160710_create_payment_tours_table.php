<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_tours', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('tours_id')->constrained();
            $table->integer('tours_id');
            $table->date('date');
            $table->integer('adult');
            $table->integer('child');
            $table->string('promocode');
            $table->float('discount');
            $table->foreignId('payment_clients_id')->constrained();
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
        Schema::dropIfExists('payment_tours');
    }
}
