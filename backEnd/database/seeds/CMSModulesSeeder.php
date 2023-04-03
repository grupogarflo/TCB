<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CMSModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //


        DB::table('cms_modules')->insert([
            ['name'=>'Usuarios','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Banner Home','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Catalogo Icons','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Categorias','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Destinos','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Relación categoria/destino','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Tours','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Tour Precio','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Tour Galeria','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Tour Video','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Tour Mapa','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Tour Imagen','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Idiomas','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Tipo de Cambio','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Bloqueo de Fechas/Dias','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Promocode','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],
            ['name'=>'Reservas','active'=>1, 'created_at'=>Carbon::now(), 'updated_at'=>Carbon::now()],

        ]);

    }
}
