<?php

use Illuminate\Database\Seeder;
use \Artesaos\Defender\Permission;

class Permisos4 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisos = [
            'cambiar-clase-pqr',
            'cambiar-funcionario-pqr',
            'modificar-radicado-contestacion-pqr',
            'eliminar-radicado-contestacion-pqr',
            'cambiar-medio-traslado-pqr',
            'crear-liquidacion-servicio-publico',
            'crear-vigencia-servicio-publico',
            'editar-vigencia-servicio-publico',
            'crear-base-gravable-servicio-publico',
            'editar-base-gravable-servicio-publico',
            'crear-descuento-servicio-publico',
            'editar-descuento-servicio-publico',
            'importar-base-gravable-servicio-publico',
            'registrar-pago-servicio-publico',
            'editar-pago-servicio-publico',
            'recalcular-liquidacion-servicio-publico',
            'importar-registros-servicio-publico',
            'crear-linea-vehiculo',
            'editar-linea-vehiculo',
            'crear-propietario-vehiculo',
            'editar-propietario-vehiculo',
            'retirar-propietario-vehiculo',
            'anular-pqr',
            'crear-motivo-anulacion-pqr',
            'editar-motivo-anulacion-pqr',
            'crear-gd-parametros',
            'editar-gd-parametros',
        ];

        foreach ($permisos as $permiso){
            Permission::firstOrNew(['name'=>$permiso, 'readable_name' => title_case(str_replace('-', ' ', $permiso)), 'created_at' => date('Y-m-d H:i:s')]);
        }
    }
}
