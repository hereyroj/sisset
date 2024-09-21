<?php

use Illuminate\Database\Seeder;
use Artesaos\Defender\Permission;
use Artesaos\Defender\Role;
use Artesaos\Defender\Defender;

class rolesypermisos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('permission_role')->truncate();
        \DB::table('role_user')->truncate();
        \DB::table('role_report')->truncate();
        \DB::table('roles')->truncate();
        \DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $roles = array(
            [
                'name' => 'Administrador',
                'permissions' => array(
                    'administrar-usuarios','restaurar-usuario','eliminar-usuario','activar-usuario','desactivar-usuario','crear-usuario','editar-usuario','ver-perfil-usuario',
                    'administrar-vehiculos','eliminar-marca-vehiculo','restaurar-marca-vehiculo','editar-marca-vehiculo','crear-marca-vehiculo','eliminar-clase-vehiculo','restaurar-clase-vehiculo','editar-clase-vehiculo','crear-clase-vehiculo','crear-combustible-vehiculo','editar-combustible-vehiculo','restaurar-combustible-vehiculo','eliminar-combustible-vehiculo','crear-carroceria-vehiculo','editar-carroceria-vehiculo','eliminar-carroceria-vehiculo','restaurar-carroceria-vehiculo','crear-vehiculo','editar-vehiculo','vincular-empresa-vehiculo','ver-empresa-vehiculo','editar-empresa-vehiculo','eliminar-servicio-vehiculo','restaurar-servicio-vehiculo','crear-servicio-vehiculo','editar-servicio-vehiculo',
                    'administrar-roles','crear-rol','consultar-rol','editar-rol','eliminar-rol','restaurar-rol',
                    'administrar-parametros','crear-empresa-parametros','editar-empresa-parametros','crear-pqr-parametros','editar-pqr-parametros','crear-tramite-parametros','editar-tramite-parametros','crear-vigencia-parametros','editar-vigencia-parametros',
                    'administrar-empresas-transporte','editar-empresa-transporte','crear-empresa-transporte','eliminar-empresa-transporte','restaurar-empresa-transporte',
                    'administrar-dependencias', 'editar-dependencia','crear-dependencia','eliminar-dependencia','restaurar-dependencia',
                    'administrar-tramites', 'editar-tramite','crear-tramite','eliminar-tramite','restaurar-tramite',
                    'administrar-calendario','importar-registros-calendario',
                    'administrar-documentos-identidad','crear-documento-identidad','editar-documento-identidad','eliminar-documento-identidad','restaurar-documento-identidad',
                    'administrar-ventanillas', 'crear-ventanilla','editar-ventanilla',
                    'ver-historial-carpeta','ver-traslado-carpeta','importar-carpetas-archivo','editar-motivo-cancelacion-carpeta','ver-estados-carpeta','editar-estado-carpeta','revertir-traslado-carpeta','trasladar-carpeta','cambiar-estado-carpeta','exportar-historial-carpeta','editar-carpeta','crear-carpeta','cancelar-carpeta','revertir-cancelacion-carpeta','eliminar-carpeta','cambiar-clase-carpeta','ver-solicitud-carpeta',
                    'entregar-carpeta-solicitud','validar-solicitud','aprobar-solicitud','ingresar-carpeta-solicitud','denegar-solicitud',
                    'solicitar-carpeta',
                    'crear-placa','editar-placa','liberar-placa',
                    'preasignar-placa','rechazar-preasignacion-placa',
                    'administrar-solicitudes-tramites', 'crear-solicitud-tramite','editar-solicitud-tramite','asignar-estado-solicitud-tramite','llamar-turno-tramite','actualizar-estado-solicitud-tramite',
                    'editar-sustrato','crear-sustrato',
                    'crear-to','editar-to','eliminar-to','restaurar-to','imprimir-to',
                    'registrar-empresa-envio', 'registrar-entrega-pqr', 'crear-modalidad-pqr','editar-modalidad-pqr','eliminar-modalidad-pqr','restaurar-modalidad-pqr',
                    'crear-sancion', 'editar-sancion', 'eliminar-sancion', 'activar-sancion',
                    'monitorear-logs',
                    'asignar-pqr','re-asignar-pqr','clasificar-pqr','editar-clasificacion-pqr','registrar-nuevo-pqr','crear-clase-pqr','modificar-clase-pqr','eliminar-clase-pqr','restaurar-clase-pqr','crear-medio-pqr','modificar-medio-pqr','eliminar-medio-pqr','restaurar-medio-pqr',
                    'crear-serie-trd', 'crear-sub-serie-trd','crear-tipo-documento-trd','editar-serie-trd','editar-sub-serie-trd','editar-tipo-documento-trd','eliminar-tipo-documento-trd','eliminar-sub-serie-trd','eliminar-serie-trd',
                    'crear-comparendo','registrar-pago-comparendo','editar-comparendo','editar-pago-comparendo',
                    'editar-edicto-comparendo','crear-edicto-comparendo','eliminar-edicto-comparendo','restaurar-edicto-comparendo','importar-edictos-comparendos',
                    'editar-edicto-foto-multa','crear-edicto-foto-multa','eliminar-edicto-foto-multa','restaurar-edicto-foto-multas','importar-edictos-foto-multas',
                )
            ]
        );

        foreach ($roles as $rol){
            $role = Role::create(['name' => $rol['name'], 'created_at' => date('Y-m-d H:i:s'),]);
            foreach ($rol['permissions'] as $permission){
                $permiso = Permission::create(['name'=>$permission, 'readable_name' => title_case(str_replace('-', ' ', $permission)), 'created_at' => date('Y-m-d H:i:s')]);
                $role->attachPermission($permiso);
            }
            $role = null;
            $permiso = null;
        }
    }
}
