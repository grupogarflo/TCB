<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('private_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id')->nullable();
            $table->unsignedBigInteger('transportation_type_id')->nullable();
            $table->unsignedBigInteger('pax_range_id')->nullable();
            $table->decimal('fake_price',8,2);
            $table->decimal('real_price',8,2);
            $table->decimal('rate_from_fake',8,2)->nullable();
            $table->decimal('rate_from_real',8,2)->nullable();
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
        Schema::dropIfExists('private_rates');
    }
}
