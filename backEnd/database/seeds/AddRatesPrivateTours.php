<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AddRatesPrivateTours extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // tulum express

        DB::table('private_rates')->insert([
            'tour_id'=>14,
            'transportation_type_id'=>1,
            'pax_range_id'=>1,
            'fake_price'=> '546.00',
            'real_price'=> '436.80',
            'rate_from_fake'=>'50.94',
            'rate_from_real'=>'40.75' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>14,
            'transportation_type_id'=>1,
            'pax_range_id'=>2,
            'fake_price'=> '579.00',
            'real_price'=> '463.20',
            'rate_from_fake'=>'50.94',
            'rate_from_real'=>'40.75' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>14,
            'transportation_type_id'=>1,
            'pax_range_id'=>3,
            'fake_price'=> '612.00',
            'real_price'=> '489.60',
            'rate_from_fake'=>'50.94',
            'rate_from_real'=>'40.75' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>14,
            'transportation_type_id'=>1,
            'pax_range_id'=>4,
            'fake_price'=> '645.00',
            'real_price'=> '516.00',
            'rate_from_fake'=>'50.94',
            'rate_from_real'=>'40.75' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>14,
            'transportation_type_id'=>1,
            'pax_range_id'=>5,
            'fake_price'=> '749.00',
            'real_price'=> '599.20',
            'rate_from_fake'=>'50.94',
            'rate_from_real'=>'40.75' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>14,
            'transportation_type_id'=>2,
            'pax_range_id'=>6,
            'fake_price'=> '782.00',
            'real_price'=> '625.60',
            'rate_from_fake'=>'50.94',
            'rate_from_real'=>'40.75' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);


        DB::table('private_rates')->insert([
            'tour_id'=>14,
            'transportation_type_id'=>2,
            'pax_range_id'=>7,
            'fake_price'=> '815.00',
            'real_price'=> '652.00',
            'rate_from_fake'=>'50.94',
            'rate_from_real'=>'40.75' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>14,
            'transportation_type_id'=>2,
            'pax_range_id'=>8,
            'fake_price'=> '848.00',
            'real_price'=> '678.40',
            'rate_from_fake'=>'50.94',
            'rate_from_real'=>'40.75' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);



        /// bacalar

        DB::table('private_rates')->insert([
            'tour_id'=>11,
            'transportation_type_id'=>1,
            'pax_range_id'=>1,
            'fake_price'=> '829.00',
            'real_price'=> '663.20',
            'rate_from_fake'=>'118.38',
            'rate_from_real'=>'94.70' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>11,
            'transportation_type_id'=>1,
            'pax_range_id'=>2,
            'fake_price'=> '995.00',
            'real_price'=> '796.00',
            'rate_from_fake'=>'118.38',
            'rate_from_real'=>'94.70' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>11,
            'transportation_type_id'=>1,
            'pax_range_id'=>3,
            'fake_price'=> '1160.00',
            'real_price'=> '928.00',
            'rate_from_fake'=>'118.38',
            'rate_from_real'=>'94.70' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>11,
            'transportation_type_id'=>1,
            'pax_range_id'=>4,
            'fake_price'=> '1326.00',
            'real_price'=> '1060.80',
            'rate_from_fake'=>'118.38',
            'rate_from_real'=>'94.70' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>11,
            'transportation_type_id'=>1,
            'pax_range_id'=>5,
            'fake_price'=> '1563.00',
            'real_price'=> '1250.40',
            'rate_from_fake'=>'118.38',
            'rate_from_real'=>'94.70' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>11,
            'transportation_type_id'=>2,
            'pax_range_id'=>6,
            'fake_price'=> '1728.00',
            'real_price'=> '1382.40',
            'rate_from_fake'=>'118.38',
            'rate_from_real'=>'94.70' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);


        DB::table('private_rates')->insert([
            'tour_id'=>11,
            'transportation_type_id'=>2,
            'pax_range_id'=>7,
            'fake_price'=> '1894.00',
            'real_price'=> '1515.20',
            'rate_from_fake'=>'118.38',
            'rate_from_real'=>'94.70' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>11,
            'transportation_type_id'=>2,
            'pax_range_id'=>8,
            'fake_price'=> '2059.00',
            'real_price'=> '1647.20',
            'rate_from_fake'=>'118.38',
            'rate_from_real'=>'94.70' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);



        //// holbox

        DB::table('private_rates')->insert([
            'tour_id'=>12,
            'transportation_type_id'=>1,
            'pax_range_id'=>1,
            'fake_price'=> '1090.00',
            'real_price'=> '872.00',
            'rate_from_fake'=>'157.38',
            'rate_from_real'=>'125.90' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>12,
            'transportation_type_id'=>1,
            'pax_range_id'=>2,
            'fake_price'=> '1320.00',
            'real_price'=> '1056.00',
            'rate_from_fake'=>'157.38',
            'rate_from_real'=>'125.90' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>12,
            'transportation_type_id'=>1,
            'pax_range_id'=>3,
            'fake_price'=> '1550.00',
            'real_price'=> '1240.00',
            'rate_from_fake'=>'157.38',
            'rate_from_real'=>'125.90' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>12,
            'transportation_type_id'=>1,
            'pax_range_id'=>4,
            'fake_price'=> '1780.00',
            'real_price'=> '1424.00',
            'rate_from_fake'=>'157.38',
            'rate_from_real'=>'125.90' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>12,
            'transportation_type_id'=>1,
            'pax_range_id'=>5,
            'fake_price'=> '2058.00',
            'real_price'=> '1646.40',
            'rate_from_fake'=>'157.38',
            'rate_from_real'=>'125.90' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>12,
            'transportation_type_id'=>2,
            'pax_range_id'=>6,
            'fake_price'=> '2288.00',
            'real_price'=> '1830.40',
            'rate_from_fake'=>'157.38',
            'rate_from_real'=>'125.90' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);


        DB::table('private_rates')->insert([
            'tour_id'=>12,
            'transportation_type_id'=>2,
            'pax_range_id'=>7,
            'fake_price'=> '2518.00',
            'real_price'=> '2014.40',
            'rate_from_fake'=>'157.38',
            'rate_from_real'=>'125.90' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);

        DB::table('private_rates')->insert([
            'tour_id'=>12,
            'transportation_type_id'=>2,
            'pax_range_id'=>8,
            'fake_price'=> '2748.00',
            'real_price'=> '2198.40',
            'rate_from_fake'=>'157.38',
            'rate_from_real'=>'125.90' ,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'=>Carbon::now()->format('Y-m-d H:i:s')

        ]);










    }
}
