<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'name' => 'Jorge Cortes',
            'email' => 'hcortes@grupogarflo.com',
            'password' => Hash::make("hcortes"),
            'type_user' => 1
        ]);
        DB::table('users')->insert([
            'name' => 'Alejandro Perez',
            'email' => 'aperez@grupogarflo.com',
            'password' => Hash::make("aperez"),
            'type_user' => 1
            
        ]);
        DB::table('users')->insert([
            'name' => 'Diana Castaneda',
            'email' => 'dcastaneda@grupogarflo.com',
            'password' => Hash::make("dcastaneda"),
            'type_user' => 1
        ]);
        DB::table('users')->insert([
            'name' => 'Lore Avila',
            'email' => 'lavila@grupogarflo.com',
            'password' => Hash::make("lavila"),
            'type_user' => 1
        ]);
    }
}
