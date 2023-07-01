<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AddPrivateRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('transportation_types')->insert([
            'name_esp'=>'VAN',
            'name_eng'=>'VAN',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('transportation_types')->insert([
            'name_esp'=>'SPRINTER',
            'name_eng'=>'SPRINTER',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);





        DB::table('pax_ranges')->insert([
            'name_esp'=>'1 a 4',
            'name_eng'=>'1 to 4',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('pax_ranges')->insert([
            'name_esp'=>'5 a 6',
            'name_eng'=>'5 to 6',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);



        DB::table('pax_ranges')->insert([
            'name_esp'=>'7 a 8',
            'name_eng'=>'7 to 8',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('pax_ranges')->insert([
            'name_esp'=>'9 a 10',
            'name_eng'=>'9 to 10',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('pax_ranges')->insert([
            'name_esp'=>'11 a 12',
            'name_eng'=>'11 to 12',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('pax_ranges')->insert([
            'name_esp'=>'13 a 14',
            'name_eng'=>'13 to 14',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('pax_ranges')->insert([
            'name_esp'=>'15 a 16',
            'name_eng'=>'15 to 16',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('pax_ranges')->insert([
            'name_esp'=>'17 a 18',
            'name_eng'=>'17 to 18',
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);









    }
}
