<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CMSActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 
        DB::table('cms_actions')->insert([
            ['name'=>'Nuevo','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Editar','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Guardar','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Eliminar','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Ver','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Descarga reporte','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Permisos','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Apagar tour','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Reembolso','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Editar fechas del tour','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Cancelar tours','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

        ]);
    }
}
