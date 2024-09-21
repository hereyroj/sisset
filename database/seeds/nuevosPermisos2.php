<?php

use Illuminate\Database\Seeder;

class nuevosPermisos2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [
            'crear-tipo-comparendo',
            'editar-tipo-comparendo',
            'crear-tipo-inmovilziacion',
            'editar-tipo-inmovilziacion',
            'crear-infraccion',
            'editar-infraccion',
            'crear-tipo-sancion',
            'editar-tipo-sancion',
            'administrar-empresas-mensajeria',
            'editar-empresa-mensajeria',
            'crear-empresa-mensajeria'
        ];

        foreach ($permisos as $permiso){
            $dbPermiso = \Artesaos\Defender\Permission::where('name', $permiso)->first();
            if($dbPermiso == null){
                \Artesaos\Defender\Permission::create(['name'=>$permiso, 'readable_name' => title_case(str_replace('-', ' ', $permiso)), 'created_at' => date('Y-m-d H:i:s')]);
            }
        }
    }
}
