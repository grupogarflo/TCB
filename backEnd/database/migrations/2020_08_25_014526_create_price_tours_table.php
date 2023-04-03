<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_tours', function (Blueprint $table) {
            $table->id();
            $table->float("price_fake_adult");
            $table->float("price_fake_child");
            $table->float("price_real_adult");
            $table->float("price_real_child");
            $table->float('special_price')->nullable(false)->default(0);
            //$table->foreignId('tour_id')->constrained();
            $table->foreignId('tour_contents_id')->constrained();
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
        Schema::dropIfExists('price_tours');
    }
}
