<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActionsModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        Db::table('action_modules')->insert([
            ['cms_modules_id'=>1, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>1, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>1, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>1, 'cms_actions_id'=>7, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            
            ['cms_modules_id'=>2, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>2, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>2, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['cms_modules_id'=>3, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>3, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>3, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],


            ['cms_modules_id'=>4, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>4, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>4, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],


            ['cms_modules_id'=>5, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>5, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>5, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],



            ['cms_modules_id'=>6, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>6, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>6, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            
            ['cms_modules_id'=>7, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>7, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>7, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>7, 'cms_actions_id'=>8, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            
            
            ['cms_modules_id'=>8, 'cms_actions_id'=>3, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>9, 'cms_actions_id'=>3, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],


            ['cms_modules_id'=>10, 'cms_actions_id'=>3, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>11, 'cms_actions_id'=>3, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>12, 'cms_actions_id'=>3, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],



            ['cms_modules_id'=>12, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>9, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],


            ['cms_modules_id'=>13, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>13, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>13, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            
            ['cms_modules_id'=>14, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>14, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>14, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],


            ['cms_modules_id'=>15, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>15, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>15, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],



            ['cms_modules_id'=>16, 'cms_actions_id'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>16, 'cms_actions_id'=>2, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>16, 'cms_actions_id'=>4, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

            ['cms_modules_id'=>17, 'cms_actions_id'=>5, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>17, 'cms_actions_id'=>6, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>17, 'cms_actions_id'=>10, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>17, 'cms_actions_id'=>9, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['cms_modules_id'=>17, 'cms_actions_id'=>11, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],




        ]);
    }
}
