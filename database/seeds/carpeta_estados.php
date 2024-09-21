<?php

use Illuminate\Database\Seeder;

class carpeta_estados extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados_carpetas = array(
            0 => ['name'=>'EN INVENTARIO', 'estado_carpeta'=>'SI'],
            1 => ['name'=>'SIN INVENTARIAR', 'estado_carpeta'=>'NO'],
            2 => ['name'=>'PERDIDA', 'estado_carpeta'=>'NO'],
            3 => ['name'=>'EN RECONTRUCCIÓN', 'estado_carpeta'=>'NO'],
            4 => ['name'=>'RESTRINGIDA', 'estado_carpeta'=>'NO'],
            5 => ['name'=>'EN AUDITORIA', 'estado_carpeta'=>'NO'],
        );

        foreach ($estados_carpetas as $estado)
        {
            DB::table('archivo_carpeta_estado')->insert([
                'name' => $estado['name'],
                'estado_carpeta' => $estado['estado_carpeta'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
