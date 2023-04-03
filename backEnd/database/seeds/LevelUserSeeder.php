<?php

use App\level_user;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

      
        DB::table('level_users')->insert([
            ['users_id'=>1, 'cms_modules_id'=>1, 'cms_actions_id'=>1],
            ['users_id'=>1, 'cms_modules_id'=>1, 'cms_actions_id'=>2],
            ['users_id'=>1, 'cms_modules_id'=>1, 'cms_actions_id'=>4],
            ['users_id'=>1, 'cms_modules_id'=>1, 'cms_actions_id'=>7],

            ['users_id'=>2, 'cms_modules_id'=>1, 'cms_actions_id'=>1],
            ['users_id'=>2, 'cms_modules_id'=>1, 'cms_actions_id'=>2],
            ['users_id'=>2, 'cms_modules_id'=>1, 'cms_actions_id'=>4],
            ['users_id'=>2, 'cms_modules_id'=>1, 'cms_actions_id'=>7],

            ['users_id'=>3, 'cms_modules_id'=>1, 'cms_actions_id'=>1],
            ['users_id'=>3, 'cms_modules_id'=>1, 'cms_actions_id'=>2],
            ['users_id'=>3, 'cms_modules_id'=>1, 'cms_actions_id'=>4],
            ['users_id'=>3, 'cms_modules_id'=>1, 'cms_actions_id'=>7],

            ['users_id'=>4, 'cms_modules_id'=>1, 'cms_actions_id'=>1],
            ['users_id'=>4, 'cms_modules_id'=>1, 'cms_actions_id'=>2],
            ['users_id'=>4, 'cms_modules_id'=>1, 'cms_actions_id'=>4],
            ['users_id'=>4, 'cms_modules_id'=>1, 'cms_actions_id'=>7],
        ]);
    }
}
