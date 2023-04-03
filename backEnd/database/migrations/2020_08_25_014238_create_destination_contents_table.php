<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDestinationContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destination_contents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('url');
            $table->integer('show_home')->nullable(false)->default(0);
            $table->string('img');
            $table->text('description');
            $table->text('description_footer');
            $table->integer('active')->nullable(false)->default(1);
            $table->text("meta_title");
            $table->text("meta_description");
            $table->text("meta_keywords");
            $table->foreignId('destination_id')->constrained();
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
        Schema::dropIfExists('destination_contents');
    }
}
