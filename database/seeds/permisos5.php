<?php

use Illuminate\Database\Seeder;
use Artesaos\Defender\Permission;
use Artesaos\Defender\Role;
use Artesaos\Defender\Defender;
use App\User;

class permisos5 extends Seeder
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

        $permisos = [
            'publicacion-crear',
            'publicacion-crear-categoria',
            'publicacion-crear-estado',
            'publicacion-editar',
            'publicacion-editar-categoria',
            'publicacion-editar-estado',
            'tarjeta-operacion-crear',
            'tarjeta-operacion-editar',
            'tarjeta-operacion-imprimir',
            'sancion-crear',
            'sancion-editar',
            'sancion-eliminar',
            'sancion-crear-tipo',
            'sancion-editar-tipo',
            'comparendo-crear',
            'comparendo-editar',
            'comparendo-registrar-pago',
            'comparendo-editar-pago',
            'comparendo-crear-tipo',
            'comparendo-crear-tipo-inmovilziacion',
            'comparendo-crear-infraccion',
            'comparendo-editar-tipo',
            'comparendo-editar-tipo-inmoviliziacion',
            'comparendo-editar-infraccion',
            'comparendo-crear-modalidad-pago',
            'comparendo-editar-modalidad-pago',
            'comparendo-sancionar',
            'comparendo-crear-tipo-infractor',
            'comparendo-editar-tipo-infractor',
            'comparendo-crear-licencia-categoria',
            'comparendo-editar-licencia-categoria',
            'comparendo-crear-entidad',
            'comparendo-editar-entidad',
            'edicto-comparendo-crear',
            'edicto-comparendo-editar',
            'edicto-comparendo-eliminar',
            'edicto-comparendo-restaurar',
            'edicto-comparendo-importar',
            'edicto-foto-multa-importar',
            'edicto-foto-multa-eliminar',
            'edicto-foto-multa-editar',
            'edicto-foto-multa-crear',
            'mandamiento-vincular-comparendo',
            'mandamiento-editar-medio-notificacion',
            'mandamiento-crear-medio-notificacion',
            'mandamiento-editar-tipo-notificacion',
            'mandamiento-crear--tipo-notificacion',
            'mandamiento-actualizar-entrega',
            'mandamiento-crear-entrega',
            'mandamiento-editar-finalizacion',
            'mandamiento-crear-finalizacion',
            'mandamiento-editar-tipo-finalizacion',
            'mandamiento-crear-tipo-finalizacion',
            'mandamiento-editar-motivo-devolucion',
            'mandamiento-crear-motivo-devolucion',
            'mandamiento-editar-devolucion',
            'mandamiento-crear-devolucion',
            'mandamiento-editar-notificacion',
            'mandamiento-crear-notificacion',
            'mandamiento-actualizar-notificacion-medio',
            'mandamiento-crear-notificacion-medio',
            'mandamiento-editar',
            'mandamiento-crear',
            'pqr-asignar',
            'pqr-clasificar',
            'pqr-editar-clasificacion',
            'pqr-crear',
            'pqr-responder',
            'pqr-modificar-clase',
            'pqr-crear-clase',
            'pqr-eliminar-clase',
            'pqr-restaurar-clase',
            'pqr-modificar-medio',
            'pqr-crear-medio',
            'pqr-eliminar-medio',
            'pqr-restaurar-medio',
            'pqr-crear-modalidad',
            'pqr-editar-modalidad',
            'pqr-eliminar-modalidad',
            'pqr-restaurar-modalidad',
            'pqr-cambiar-clase',
            'pqr-cambiar-funcionario',
            'pqr-modificar-radicado-contestacion',
            'pqr-eliminar-radicado-contestacion',
            'pqr-cambiar-medio-traslado',
            'pqr-anular',
            'pqr-reasignar',
            'pqr-crear-motivo-anulacion',
            'pqr-editar-motivo-anulacion',
            'trd-crear-serie',
            'trd-crear-sub-serie',
            'trd-crear-tipo-documento',
            'trd-editar-serie',
            'trd-editar-sub-serie',
            'trd-editar-tipo-documento',
            'trd-eliminar-tipo-documento',
            'trd-eliminar-sub-serie',
            'trd-eliminar-serie',
            'parametro-crear-empresa',
            'parametro-editar-empresa',
            'parametro-crear-pqr',
            'parametro-editar-pqr',
            'parametro-crear-tramite',
            'parametro-editar-tramite',
            'parametro-crear-vigencia',
            'parametro-editar-vigencia',
            'parametro-crear-gd',
            'parametro-editar-gd',
            'log-monitorear',
            'usuario-administrar',
            'usuario-crear',
            'usuario-editar',
            'usuario-ver-perfil',
            'usuario-desactivar',
            'usuario-activar',
            'usuario-eliminar',
            'usuario-restaurar',
            'rol-administrar',
            'rol-crear',
            'rol-consultar',
            'rol-editar',
            'empresa-transporte-administrar',
            'empresa-transporte-editar',
            'empresa-transporte-crear',
            'empresa-mensajeria-administrar',
            'empresa-mensajeria-editar',
            'empresa-mensajeria-crear',
            'dependencia-administrar',
            'dependencia-eliminar',
            'dependencia-restaurar',
            'dependencia-editar',
            'dependencia-crear',
            'tramite-administrar',
            'tramite-eliminar',
            'tramite-restaurar',
            'tramite-editar',
            'tramite-crear',
            'tramite-requerimiento-crear',
            'tramite-requerimiento-editar',
            'tramite-grupo-administrar',
            'tramite-grupo-eliminar',
            'tramite-grupo-restaurar',
            'tramite-grupo-editar',
            'tramite-grupo-crear',
            'calendario-administrar',
            'calendario-importar-registros',
            'calendario-crear-registro',
            'calendario-editar-registro',
            'documentos-identidad-administrar',
            'documentos-identidad-crear',
            'documentos-identidad-editar',
            'documentos-identidad-eliminar',
            'documentos-identidad-restaurar',
            'ventanilla-administrar',
            'ventanilla-crear',
            'ventanilla-editar',
            'carpeta-ver-historial',
            'carpeta-ver-traslado',
            'carpeta-importar',
            'carpeta-crear-motivo-cancelacion',
            'carpeta-editar-motivo-cancelacion',
            'carpeta-ver-estados',
            'carpeta-editar-estado',
            'carpeta-revertir-traslado',
            'carpeta-trasladar',
            'carpeta-cambiar-estado',
            'carpeta-exportar-historial',
            'carpeta-editar',
            'carpeta-crear',
            'carpeta-cancelar',
            'carpeta-revertir-cancelacion',
            'carpeta-eliminar',
            'carpeta-cambiar-clase',
            'carpeta-ver-solicitud',
            'solicitud-carpeta-entregar',
            'solicitud-carpeta-validar',
            'solicitud-carpeta-aprobar',
            'solicitud-carpeta-ingresar',
            'solicitud-carpeta-denegar',
            'solicitud-carpeta-crear-tipo-validacion',
            'solicitud-carpeta-editar-tipo-validacion',
            'liquidacion-vehiculo-liquidar',
            'liquidacion-vehiculo-crear',
            'liquidacion-vehiculo-crear-vigencia',
            'liquidacion-vehiculo-editar-vigencia',
            'liquidacion-vehiculo-crear-base-gravable',
            'liquidacion-vehiculo-editar-base-gravable',
            'liquidacion-vehiculo-crear-descuento',
            'liquidacion-vehiculo-editar-descuento',
            'liquidacion-vehiculo-importar-base-gravable',
            'liquidacion-vehiculo-registrar-pago',
            'liquidacion-vehiculo-editar-pago',
            'liquidacion-vehiculo-recalcular',
            'liquidacion-vehiculo-importar-registros',
            'liquidacion-vehiculo-crear-clase-grupo',
            'liquidacion-vehiculo-editar-clase-grupo',
            'liquidacion-vehiculo-crear-cilindraje-grupo',
            'liquidacion-vehiculo-editar-cilindraje-grupo',
            'liquidacion-vehiculo-crear-bateria-grupo',
            'liquidacion-vehiculo-editar-bateria-grupo',
            'vehiculo-administrar',
            'vehiculo-eliminar-marca',
            'vehiculo-restaurar-marca',
            'vehiculo-eliminar-clase',
            'vehiculo-restaurar-clase',
            'vehiculo-eliminar-carroceria',
            'vehiculo-restaurar-carroceria',
            'vehiculo-eliminar-combustible',
            'vehiculo-restaurar-combustible',
            'vehiculo-crear-clase',
            'vehiculo-crear-marca',
            'vehiculo-crear-combustible',
            'vehiculo-crear-carroceria',
            'vehiculo-editar-clase',
            'vehiculo-editar-marca',
            'vehiculo-editar-carroceria',
            'vehiculo-editar-combustible',
            'vehiculo-crear',
            'vehiculo-editar',
            'vehiculo-vincular-empresa',
            'vehiculo-ver-empresa',
            'vehiculo-editar-empresa',
            'vehiculo-eliminar-servicio',
            'vehiculo-restaurar-servicio',
            'vehiculo-crear-servicio',
            'vehiculo-editar-servicio',
            'vehiculo-crear-linea',
            'vehiculo-editar-linea',
            'vehiculo-crear-propietario',
            'vehiculo-editar-propietario',
            'vehiculo-retirar-propietario',
            'vehiculo-crear-tipo-bateria',
            'vehiculo-editar-tipo-bateria',
            'placa-administrar',
            'placa-crear',
            'placa-editar',
            'placa-liberar',
            'solicitud-tramite-administrar',
            'solicitud-tramite-crear',
            'solicitud-tramite-asignar-estado',
            'solicitud-tramite-llamar-turno',
            'solicitud-tramite-actualizar-estado',
            'solicitud-tramite-editar',
            'solicitud-tramite-crear-origen',
            'solicitud-tramite-editar-origen',
            'solicitud-tramite-crear-estado',
            'solicitud-tramite-editar-estado',
            'sustrato-editar',
            'sustrato-crear',
            'sustrato-crear-tipo',
            'sustrato-editar-tipo',
            'sustrato-crear-motivo-anulacion',
            'sustrato-editar-motivo-anulacion',
            'preasignacion-crear',
            'preasignacion-rechazar',
            'preasignacion-liberar',
            'preasignacion-editar-motivo-rechazo',
            'preasignacion-eliminar-motivo-rechazo',
            'preasignacion-crear-motivo-rechazo',
            'preasignacion-matricular',
            'preasignacion-administrar',
            'administrar-parametros',
            'administrar-calendario'            
        ];

        $role = Role::create([
                'name' => 'Administrador', 'created_at' => date('Y-m-d H:i:s')
            ]);
        foreach ($permisos as $permiso){
            $permiso = Permission::create(['name'=>$permiso, 'readable_name' => title_case(str_replace('-', ' ', $permiso)), 'created_at' => date('Y-m-d H:i:s')]);
            $role->attachPermission($permiso);
        }

        $administrador = User::where('id', 1)->first();
        $administrador->attachRole($role);

        $roles = [
            'Editor',
            'Coordinador Trámites',
            'Auxiliar Trámites',
            'Auxiliar DigiTurno',
            'Coordinador Coactivo',
            'Auxiliar Coactivo',
            'Inspector',
            'Coordinador Archivo',
            'Auxiliar Archivo',
            'Auxiliar Inspección',
            'Administrador PQR',
            'Administrador TRD'            
        ];

        foreach ($roles as $rol){
            $role = Role::create(['name' => $rol, 'created_at' => date('Y-m-d H:i:s'),]);
        }
    }
}
