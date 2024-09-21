<?php

use Illuminate\Database\Seeder;
use Artesaos\Defender\Permission;

class permisos9 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [
            'notificacion-aviso-crear',
            'notificacion-aviso-editar',
            'notificacion-aviso-eliminar',
            'notificacion-aviso-crear-tipo',
            'notificacion-aviso-editar-tipo',
            'solicitud-editar-solicitante',
            'solicitud-editar-licencia'
        ];

        $role = \App\Role::where('name', 'Administrador')->first();

        foreach ($permisos as $permiso){
            $permiso = Permission::create(['name'=>$permiso, 'readable_name' => title_case(str_replace('-', ' ', $permiso)), 'created_at' => date('Y-m-d H:i:s')]);
            $role->attachPermission($permiso);
        }
    }
}
