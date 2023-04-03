<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_contents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sub_title');
            $table->string('url');
            $table->integer('rank');
            $table->string('img');
            $table->integer('top_home')->nullable(false)->default(0);
            $table->text('description_small');
            $table->text('description');
            $table->text('not_include');
            $table->string('avaible');
            $table->string('duration');
            $table->text('includes');
            $table->text('map');
            $table->text('gallery');
            $table->text('bring');
            $table->text('note');
            $table->text("meta_title");
            $table->text("meta_description");
            $table->text("meta_keywords");
            //$table->integer('peek_id')->nullable(false)->default(0); ->nullable()
            $table->string('peek_id')->nullable();
            $table->foreignId('tour_id')->constrained();
            $table->foreignId('language_id')->constrained();
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
        Schema::dropIfExists('tour_contents');
    }
}
