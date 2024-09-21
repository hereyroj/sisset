<?php

use Illuminate\Database\Seeder;
use Artesaos\Defender\Permission;

class permisos10 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [
            'parametro-to-crear',
            'parametro-to-editar',
            'sustrato-restaurar-anulado',
            'normativa-crear',
            'normativa-crear-tipo',
            'normativa-editar',
            'normativa-editar-tipo'
        ];

        $role = \App\Role::where('name', 'Administrador')->first();

        foreach ($permisos as $permiso){
            $permiso = Permission::create(['name'=>$permiso, 'readable_name' => title_case(str_replace('-', ' ', $permiso)), 'created_at' => date('Y-m-d H:i:s')]);
            $role->attachPermission($permiso);
        }
    }
}
