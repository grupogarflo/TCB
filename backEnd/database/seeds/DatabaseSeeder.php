<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
      

        $this->call([
            UserTableSeeder::class,
            LanguageTableSeeder::class,
            CMSModulesSeeder::class,
            CMSActionsSeeder::class,
            LevelUserSeeder::class,
            ActionsModulesSeeder::class

        ]);
    }
}
