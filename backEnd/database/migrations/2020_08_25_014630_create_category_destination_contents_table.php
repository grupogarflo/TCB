<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryDestinationContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_destination_contents', function (Blueprint $table) {
            $table->id();
            $table->string('url')->nullable(false);
            $table->text("description");
            $table->text("description_footer");
            $table->text("meta_title");
            $table->text("meta_description");
            $table->text("meta_keywords");
            $table->integer('active')->nullable(false)->default(1);
            //$table->foreignId('destination_id')->constrained();
            //$table->foreignId('language_id')->constrained();
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
        //Schema::dropIfExists('category_destination_contents');
        Schema::disableForeignKeyConstraints();
        Schema::drop('category_destination_contents');
        Schema::enableForeignKeyConstraints();
    }
}
