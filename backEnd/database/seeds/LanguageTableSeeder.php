<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('languages')->insert([
            'name' => 'Español',
            'abbrev' => 'esp',
            'active' => 1
        ]);
        DB::table('languages')->insert([
            'name' => 'Ingles',
            'abbrev' => 'ing',
            'active' => 1
        ]);
    }
}
