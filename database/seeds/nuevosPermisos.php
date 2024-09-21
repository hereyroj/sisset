<?php

use Illuminate\Database\Seeder;
use \Artesaos\Defender\Permission;

class nuevosPermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nuevosPermisos = array(
            'asignar-pqr',
            're-asignar-pqr',
            'administrar-calendario',
            'importar-registros-calendario',
            'clasificar-pqr',
            'editar-clasificacion-pqr',
            'generar-radicado-pqr',
            'responder-pqr',
            'editar-respuesta-pqr',
            'crear-clase-pqr',
            'modificar-clase-pqr',
            'crear-medio-pqr',
            'modificar-medio-pqr',
            'crear-coex-pqr',
            'crear-coin-pqr',
            'crear-cosa-pqr',
            'monitorear-logs',
            'administrar-usuarios',
            'ver-perfil-usuario',
            'administrar-vehiculos',
            'administrar-empresas',
            'administrar-dependencias',
            'administrar-tramites',
            'importar-edicto-foto-multa',
            'exportar-historial-carpeta',
            'ingresar-rangos-carpeta',
            'denegar-solicitud',
            'entregar-carpeta-solicitud',
            'ingresar-carpeta-solicitud',
            'crear-serie-trd',
            'crear-sub-serie-trd',
            'crear-tipo-documento-trd',
            'editar-serie-trd',
            'editar-sub-serie-trd',
            'editar-tipo-documento-trd',
            'eliminar-tipo-documento-trd',
            'eliminar-sub-serie-trd',
            'eliminar-serie-trd',
            'editar-carpeta',
            'cambiar-estado-carpeta',
            'cancelar-carpeta',
            'revertir-cancelacion-carpeta'
        );

        foreach ($nuevosPermisos as $permiso){
            Permission::updateOrCreate(['name'=>$permiso, 'readable_name' => title_case(str_replace('-', ' ', $permiso)), 'created_at' => date('Y-m-d H:i:s')]);
        }
    }
}
