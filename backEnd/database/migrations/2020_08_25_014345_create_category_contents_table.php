<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_contents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('url');
            $table->text('description');
            $table->text('description_footer');
            $table->text("meta_title");
            $table->text("meta_description");
            $table->text("meta_keywords");
            $table->foreignId('category_id')->constrained();
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
        Schema::dropIfExists('category_contents');
    }
}
