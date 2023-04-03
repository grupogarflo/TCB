<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToCategoryContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_contents', function (Blueprint $table) {
            $table->integer('orden')->nullable()->after("meta_keywords");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_contents', function (Blueprint $table) {
            $table->dropColumn('orden');
        });
    }
}
