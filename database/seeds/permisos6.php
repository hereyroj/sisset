<?php

use Illuminate\Database\Seeder;
use Artesaos\Defender\Permission;
use Artesaos\Defender\Role;

class permisos6 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [
            'acuerdo-pago-crear',
            'acuerdo-pago-editar',
            'acuerdo-pago-cuota-crear',
            'acuerdo-pago-cuota-editar',
            'acuerdo-pago-cuota-crear-pago',
            'acuerdo-pago-cuota-editar-pago',
        ];

        $role = \App\Role::where('name', 'Administrador')->first();

        foreach ($permisos as $permiso){
            $permiso = Permission::create(['name'=>$permiso, 'readable_name' => title_case(str_replace('-', ' ', $permiso)), 'created_at' => date('Y-m-d H:i:s')]);
            $role->attachPermission($permiso);
        }
    }
}
