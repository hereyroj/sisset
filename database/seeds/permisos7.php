<?php

use Artesaos\Defender\Permission;
use Illuminate\Database\Seeder;

class permisos7 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [
            'mandamiento-registrar-pago',
            'mandamiento-editar-pago',
            'solicitud-carpeta-crear-motivo-solicitud',
            'solicitud-carpeta-editar-motivo-solicitud',
            'solicitud-carpeta-crear-motivo-denegacion',
            'solicitud-carpeta-editar-motivo-denegacion',
            'solicitud-tramite-crear-motivo-descanso',
            'solicitud-editar-motivo-descanso',
            'parametro-tramite-crear',
            'parametro-tramite-editar'
        ];

        $role = \App\Role::where('name', 'Administrador')->first();

        foreach ($permisos as $permiso){
            $permiso = Permission::create(['name'=>$permiso, 'readable_name' => title_case(str_replace('-', ' ', $permiso)), 'created_at' => date('Y-m-d H:i:s')]);
            $role->attachPermission($permiso);
        }
    }
}
