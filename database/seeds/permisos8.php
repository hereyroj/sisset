<?php

use Artesaos\Defender\Permission;
use Illuminate\Database\Seeder;

class permisos8 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [
            'sustrato-crear-motivo-liberacion',
            'sustrato-editar-motivo-liberacion',
            'sustrato-liberar-consumido',
            'sustrato-anular-consumido'
        ];

        $role = \App\Role::where('name', 'Administrador')->first();

        foreach ($permisos as $permiso){
            $permiso = Permission::create(['name'=>$permiso, 'readable_name' => title_case(str_replace('-', ' ', $permiso)), 'created_at' => date('Y-m-d H:i:s')]);
            $role->attachPermission($permiso);
        }
    }
}
