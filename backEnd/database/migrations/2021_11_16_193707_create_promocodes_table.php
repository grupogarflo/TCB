<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string("code");
            $table->float("discount_adult");
            $table->float("discount_child");
            $table->date("date_start_booking");
            $table->date("date_end_booking");
            $table->date("date_start_travel");
            $table->date("date_end_travel");
            $table->string("type_discount");
            $table->string("active")->default('1');;
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
        Schema::dropIfExists('promocodes');
    }
}
