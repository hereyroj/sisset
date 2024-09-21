<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index'); 

Route::post('broadcasting/auth', 'BroadcastController@authenticate');

/*
 * Google 2FA Verify
 */
Route::post('2faVerify', function () {
    return redirect()->to('/admin/dashboard');
})->name('2faVerify')->middleware('2fa');
/*
 * Administrativo
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth','2fa','checkLockedUser','webauthn']], function () {
    /*
     * Escritorio
     */
    Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');
    /*
     * Biblioteca
     */
    Route::get('biblioteca', 'BibliotecaController@index')->name('admin.biblioteca');
    /*
     * Posts
     */
    Route::group(['prefix' => 'posts', 'middleware' => ['necesitaRoles:Administrador|Editor']], function () {

        Route::get('administrar', 'PostController@index');

        Route::get('obtenerPublicaciones', 'PostController@list');

        Route::get('obtenerCategorias', 'PostCategoryController@list');

        Route::get('obtenerEstados', 'PostStatusController@list');

        Route::get('nuevaPublicacion', 'PostController@create')->middleware('necesitaPermisos:publicacion-crear');

        Route::get('nuevaCategoria', 'PostCategoryController@create')->middleware('necesitaPermisos:publicacion-crear-categoria');

        Route::get('nuevoEstado', 'PostStatusController@create')->middleware('necesitaPermisos:publicacion-crear-estado');

        Route::post('nuevaPublicacion', 'PostController@store')->middleware('necesitaPermisos:publicacion-crear');

        Route::post('nuevaCategoria', 'PostCategoryController@store')->middleware('necesitaPermisos:publicacion-crear-categoria');

        Route::post('nuevoEstado', 'PostStatusController@store')->middleware('necesitaPermisos:publicacion-crear-estado');

        Route::get('editarPublicacion/{id}', 'PostController@edit')->middleware('necesitaPermisos:publicacion-editar');

        Route::get('editarCategoria/{id}', 'PostCategoryController@edit')->middleware('necesitaPermisos:publicacion-editar-categoria');

        Route::get('editarEstado/{id}', 'PostStatusController@edit')->middleware('necesitaPermisos:publicacion-editar-estado');

        Route::post('editarPublicacion', 'PostController@update')->middleware('necesitaPermisos:publicacion-editar');

        Route::post('editarCategoria', 'PostCategoryController@update')->middleware('necesitaPermisos:publicacion-editar-categoria');

        Route::post('editarEstado', 'PostStatusController@update')->middleware('necesitaPermisos:publicacion-editar-estado');

    });
    /*
     * Chats
     */
    Route::group(['prefix' => 'chat'], function () {

        Route::get('obtenerMensajes/{origen}/{id}', 'ChatController@obtenerMensajes');

        Route::post('enviarMensaje', 'ChatController@enviarMensaje');

        Route::get('obtenerMensaje/{id}', 'ChatController@obtenerMensaje');

        Route::get('ingrearChatRoom/{id}', 'ChatController@ingrearChatRoom');

        Route::get('abandonarChatRoom/{id}', 'ChatController@abandonarChatRoom');

        Route::get('nuevoChatRoom', 'ChatController@nuevoChatRoom');

        Route::post('crearChatRoom', 'ChatController@crearChatRoom');

        Route::get('openChatBox', 'ChatController@openChatBox');

        Route::get('obtenerListadoUsuarios', 'ChatController@obtenerUsuarios');

        Route::get('obtenerListadoRooms', 'ChatController@obtenerRooms');

        Route::get('enviarArchivos/{id}/{origen}', function($id, $origen){
            return view('admin.chat.chat_attachments', ['id' => $id, 'origen' => $origen])->render();
        });

        Route::post('enviarArchivos', 'ChatController@enviarArchivos');

        Route::get('downloadFile/{id}', 'ChatController@downloadFile');

        Route::get('markAsRead/{id}', 'ChatController@markAsRead');

    });
    /*
     * Usuario en línea
     */
    Route::get('whoIsOnline', 'DashboardController@whoIsOnline');
    /*
     * Notificaciones de usuario
     */
    Route::group(['prefix' => 'notificaciones'], function () {

        Route::get('obtenerTodas', 'DashboardController@notificaciones_obtenerTodas');

        Route::get('marcarTodasLeidas', 'DashboardController@notificaciones_marcarTodasLeidas');

        Route::get('ver/{id}', 'DashboardController@notificaciones_verNotificacion');

        Route::get('ultimas', 'DashboardController@notificaciones_ultimas');

        Route::get('obtener/{id}', 'DashboardController@obtenerNotificacion');
    });
    /*
     * Cuenta de usuario
     */
    Route::group(['prefix' => 'cuenta'], function () {

        Route::get('perfil', 'AccountController@viewProfile')->name('admin.cuenta.perfil');

        Route::post('cambiarPassword', 'AccountController@changePassword');

        Route::post('actualizarPerfil', 'AccountController@actualizarPerfil');

        Route::post('establecerPIN', 'AccountController@setPIN');

        Route::post('cambiarPIN', 'AccountController@changePIN');

        Route::get('activar2fa', 'AccountController@activar2fa');

        Route::post('registrar2fa', 'AccountController@registrar2fa');

        Route::get('desactivar2fa', 'AccountController@desactivar2fa');

        Route::post('desactivar2fa', 'AccountController@disable2fa');

        Route::get('desactivarU2f', 'AccountController@desactivarU2f');

        Route::post('desactivarU2f', 'AccountController@disableU2f');

    });
    /*
     * Inspeccion
     */
    Route::group([
        'prefix' => 'inspeccion',
        'middleware' => 'necesitaRoles:Administrador|Inspector|Auxiliar Inspección|Coordinador Trámites',
    ], function () {

        Route::group(['prefix' => 'comparendos'], function () {

            Route::get('administrar', 'ComparendoController@administrar');

            Route::get('nuevo', 'ComparendoController@nuevo')->middleware('necesitaPermisos:comparendo-crear');

            Route::post('nuevo', 'ComparendoController@crear')->middleware('necesitaPermisos:comparendo-crear');

            Route::get('obtenerInfracciones/{id}', 'ComparendoController@obtenerInfracciones');

            Route::get('obtenerComparendos/{page?}', 'ComparendoController@obtenerComparendos');

            Route::get('verInfractor/{id}', 'ComparendoController@verInfractor');

            Route::get('verInmovilizacion/{id}', 'ComparendoController@verInmovilizacion');

            Route::get('verUbicacion/{id}', 'ComparendoController@verUbicacion');

            Route::get('verTestigo/{id}', 'ComparendoController@verTestigo');

            Route::get('verVehiculo/{id}', 'ComparendoController@verVehiuclo');

            Route::get('verAgente/{id}', 'ComparendoController@verAgente');

            Route::get('registrarPago/{id}', 'ComparendoController@realizarPago')->middleware('necesitaPermisos:comparendo-registrar-pago');

            Route::post('registrarPago', 'ComparendoController@registrarPago')->middleware('necesitaPermisos:comparendo-registrar-pago');

            Route::get('verPago/{id}', 'ComparendoController@verPago');

            Route::get('editarComparendo/{id}', 'ComparendoController@editar')->middleware('necesitaPermisos:comparendo-editar');

            Route::post('editarComparendo', 'ComparendoController@actualizar')->middleware('necesitaPermisos:comparendo-editar');

            Route::get('editarPago/{id}', 'ComparendoController@editarPago')->middleware('necesitaPermisos:comparendo-editar-pago');

            Route::post('editarPago', 'ComparendoController@actualizarPago')->middleware('necesitaPermisos:comparendo-editar-pago');

            Route::get('obtenerDescripcionInfraccion/{id}', 'ComparendoController@obtenerDescripcionInfraccion');

            Route::get('obtenerComparendo/{id}', 'ComparendoController@obtenerComparendo');

            Route::get('obtenerConsginacionPago/{id}', 'ComparendoController@obtenerPagoConsignacion');

            Route::get('obtenerListadoInfracciones/{page?}', 'ComparendoController@obtenerListadoInfracciones');

            Route::get('obtenerListadoTiposComparendos/{page?}', 'ComparendoController@obtenerListadoTiposComparendos');

            Route::get('obtenerListadoTiposInmovilizaciones/{page?}', 'ComparendoController@obtenerListadoTiposInmovilizaciones');

            Route::get('nuevoTipoComparendo','ComparendoController@nuevoTipoComparendo')->middleware('necesitaPermisos:comparendo-crear-tipo');

            Route::get('nuevoTipoInmovilizacion','ComparendoController@nuevoTipoInmovilizacion')->middleware('necesitaPermisos:comparendo-crear-tipo-inmovilziacion');

            Route::get('nuevaInfraccion','ComparendoController@nuevaInfraccion')->middleware('necesitaPermisos:comparendo-crear-infraccion');

            Route::post('nuevoTipoComparendo','ComparendoController@crearTipoComparendo')->middleware('necesitaPermisos:comparendo-crear-tipo');

            Route::post('nuevoTipoInmovilizacion','ComparendoController@crearTipoInmovilizacion')->middleware('necesitaPermisos:comparendo-crear-tipo-inmovilziacion');

            Route::post('nuevaInfraccion','ComparendoController@crearInfraccion')->middleware('necesitaPermisos:comparendo-crear-infraccion');

            Route::get('editarTipoComparendo/{id}','ComparendoController@editarTipoComparendo')->middleware('necesitaPermisos:comparendo-editar-tipo');

            Route::get('editarTipoInmovilizacion/{id}','ComparendoController@editarTipoInmovilizacion')->middleware('necesitaPermisos:comparendo-editar-tipo-inmoviliziacion');

            Route::get('editarInfraccion/{id}','ComparendoController@editarInfraccion')->middleware('necesitaPermisos:comparendo-editar-infraccion');

            Route::post('editarTipoComparendo','ComparendoController@actualizarTipoComparendo')->middleware('necesitaPermisos:comparendo-editar-tipo');

            Route::post('editarTipoInmovilizacion','ComparendoController@actualizarTipoInmovilizacion')->middleware('necesitaPermisos:comparendo-editar-tipo-inmoviliziacion');

            Route::post('editarInfraccion','ComparendoController@actualizarInfraccion')->middleware('necesitaPermisos:comparendo-editar-infraccion');

            Route::get('obtenerListadoModalidadesPago/{page?}', 'ComparendoController@obtenerListadoModalidadesPago');

            Route::get('nuevaModalidadPago','ComparendoController@nuevaModalidadPago')->middleware('necesitaPermisos:comparendo-crear-modalidad-pago');

            Route::post('nuevaModalidadPago','ComparendoController@crearModalidadPago')->middleware('necesitaPermisos:comparendo-crear-modalidad-pago');

            Route::get('editarModalidadPago/{id}','ComparendoController@editarModalidadPago')->middleware('necesitaPermisos:comparendo-editar-modalidad-pago');

            Route::post('editarModalidadPago', 'ComparendoController@actualizarModalidadPago')->middleware('necesitaPermisos:comparendo-editar-modalidad-pago');

            Route::get('sancionar', 'ComparendoController@sancionarF1')->middleware('necesitaPermisos:comparendo-sancionar');

            Route::post('sancionar', 'ComparendoController@sancionarF2')->middleware('necesitaPermisos:comparendo-sancionar');

            Route::get('nuevoTipoInfractor', 'ComparendoController@tipoInfractor_nuevo')->middleware('necesitaPermisos:comparendo-crear-tipo-infractor');

            Route::post('crearTipoInfractor', 'ComparendoController@tipoInfractor_crear')->middleware('necesitaPermisos:comparendo-crear-tipo-infractor');

            Route::get('editarTipoInfractor/{id}', 'ComparendoController@tipoInfractor_editar')->middleware('necesitaPermisos:comparendo-editar-tipo-infractor');

            Route::post('actualizarTipoInfractor', 'ComparendoController@tipoInfractor_actualizar')->middleware('necesitaPermisos:comparendo-editar-tipo-infractor');

            Route::get('obtenerTiposInfractores/{page?}', 'ComparendoController@tipoInfractor_obtenerTodos');

            Route::get('obtenerLicenciaCategorias', 'ComparendoController@licenciaCategoria_obtenerListado');

            Route::get('nuevaLicenciaCategoria', 'ComparendoController@licenciaCategoria_nuevo')->middleware('necesitaPermisos:comparendo-crear-licencia-categoria');

            Route::post('crearLicenciaCategoria', 'ComparendoController@licenciaCategoria_crear')->middleware('necesitaPermisos:comparendo-crear-licencia-categoria');

            Route::get('editarLicenciaCategoria/{id}', 'ComparendoController@licenciaCategoria_editar')->middleware('necesitaPermisos:comparendo-editar-licencia-categoria');

            Route::post('actualizarLicenciaCategoria', 'ComparendoController@licenciaCategoria_actualizar')->middleware('necesitaPermisos:comparendo-editar-licencia-categoria');

            Route::get('obtenerEntidades', 'ComparendoController@entidad_obtenerListado');

            Route::get('nuevaEntidad', 'ComparendoController@entidad_nuevo')->middleware('necesitaPermisos:comparendo-crear-entidad');

            Route::post('crearEntidad', 'ComparendoController@entidad_crear')->middleware('necesitaPermisos:comparendo-crear-entidad');

            Route::get('editarEntidad/{id}', 'ComparendoController@entidad_editar')->middleware('necesitaPermisos:comparendo-editar-entidad');

            Route::post('actualizarEntidad', 'ComparendoController@entidad_actualizar')->middleware('necesitaPermisos:comparendo-editar-entidad');

            Route::get('filtrar/{parametros}/{valor}/{page?}', 'ComparendoController@filtrarComparendos');

        });

        Route::group(['prefix' => 'AcuerdosPagos'], function () {

            Route::get('administrar', 'AcuerdoPagoController@index');
            
            Route::get('obtenerAcuerdosPagos/{page?}', 'AcuerdoPagoController@getAll');        

            Route::get('nuevo', 'AcuerdoPagoController@create')->middleware('necesitaPermisos:acuerdo-pago-crear');

            Route::post('nuevo', 'AcuerdoPagoController@store')->middleware('necesitaPermisos:acuerdo-pago-crear');

            Route::get('editar/{id}', 'AcuerdoPagoController@edit')->middleware('necesitaPermisos:acuerdo-pago-editar');

            Route::post('editar', 'AcuerdoPagoController@update')->middleware('necesitaPermisos:acuerdo-pago-editar');

            Route::get('obtenerCuotasAcuerdoPago/{id}', 'AcuerdoPagoController@getCuotas');
            
            Route::get('obtenerConsignacionCuota/{id}', 'AcuerdoPagoController@');

            Route::get('obtenerFacturaCuota/{id}', 'AcuerdoPagoController@');

            Route::get('editarCuota/{id}', 'AcuerdoPagoController@editCuota')->middleware('necesitaPermisos:acuerdo-pago-cuota-editar');

            Route::post('editarCuota', 'AcuerdoPagoController@updateCuota')->middleware('necesitaPermisos:acuerdo-pago-cuota-editar');

            Route::get('pagarCuota/{id}', 'AcuerdoPagoController@pagarCuota')->middleware('necesitaPermisos:acuerdo-pago-cuota-crear-pago');

            Route::post('pagarCuota', 'AcuerdoPagoController@registrarPagoCuota')->middleware('necesitaPermisos:acuerdo-pago-cuota-crear-pago');

            Route::get('editarPagoCuota/{id}', 'AcuerdoPagoController@editPagoCuota')->middleware('necesitaPermisos:acuerdo-pago-cuota-editar-pago');

            Route::post('editarPagoCuota', 'AcuerdoPagoController@updatePagoCuota')->middleware('necesitaPermisos:acuerdo-pago-cuota-editar-pago');

            Route::get('obtenerConsignacionCuota/{id}', 'AcuerdoPagoController@obtenerConsignacionCuota');

            Route::get('obtenerFacturaCuota/{id}', 'AcuerdoPagoController@obtenerFacturaCuota');

            Route::get('verDeudor/{id}', 'AcuerdoPagoController@verDeudor');

            Route::get('filtrarAcuerdosPagos/{parametro}/{valor}', 'AcuerdoPagoController@filtrarAcuerdosPagos');

            Route::get('filtrar/{parametros}/{valor}/{page?}', 'AcuerdoPagoController@filtrarAcuerdosPagos');

        });

    });
    /*
     * Cobro coactivo
     */
    Route::group([
        'prefix' => 'coactivo',
        'middleware' => 'necesitaRoles:Administrador|Coordinador Coactivo|Auxiliar Coactivo',
    ], function () {

        Route::group(['prefix' => 'edictos'], function () {

            Route::group(['prefix' => 'comparendos'], function () {

                Route::get('obtenerComparendos/{page?}', 'CoactivoController@obtenerComparendos');

                Route::get('crearComparendo', 'CoactivoController@nuevaNotificacionComparendo')->middleware('necesitaPermisos:edicto-comparendo-crear');

                Route::post('crearComparendo', 'CoactivoController@crearComparendo')->middleware('necesitaPermisos:edicto-comparendo-crear');

                Route::get('administrar', 'CoactivoController@administrarComparendos');

                Route::get('cargarComparendo/{id}', 'CoactivoController@cargarComparendo')->middleware('necesitaPermisos:edicto-comparendo-editar');

                Route::post('editarComparendo', 'CoactivoController@editarComparendo')->middleware('necesitaPermisos:edicto-comparendo-editar');

                Route::get('eliminarComparendo/{id}', 'CoactivoController@eliminarComparendo')->middleware('necesitaPermisos:edicto-comparendo-eliminar');

                Route::get('restaurarComparendo/{id}', 'CoactivoController@eliminarComparendo')->middleware('necesitaPermisos:edicto-comparendo-restaurar');

                Route::post('importar', 'CoactivoController@importarComparendos')->middleware('necesitaPermisos:edicto-comparendo-importar');

                Route::get('filtrarBusqueda/{parametro}/{page?}', 'CoactivoController@comparendos_filtrarBusqueda');

            });

            Route::group(['prefix' => 'fotoMultas'], function () {

                Route::get('obtenerFotoMultas/{page?}', 'CoactivoController@obtenerFotoMultas');

                Route::get('crearFotoMulta', 'CoactivoController@nuevaNotificacionFotoMulta')->middleware('necesitaPermisos:edicto-foto-multa-crear');

                Route::post('crearFotoMulta', 'CoactivoController@crearFotoMulta')->middleware('necesitaPermisos:edicto-foto-multa-crear');

                Route::get('administrar', 'CoactivoController@administrarFotoMultas');

                Route::get('cargarFotoMulta/{id}', 'CoactivoController@cargarFotoMulta')->middleware('necesitaPermisos:edicto-foto-multa-editar');

                Route::post('editarFotoMulta', 'CoactivoController@editarFotoMulta')->middleware('necesitaPermisos:edicto-foto-multa-editar');

                Route::get('eliminarFotoMulta/{id}', 'CoactivoController@eliminarFotoMulta')->middleware('necesitaPermisos:edicto-foto-multa-eliminar');

                Route::post('importar', 'CoactivoController@importarFotoMultas')->middleware('necesitaPermisos:edicto-foto-multa-importar');

                Route::get('filtrarBusqueda/{parametro}/{page?}', 'CoactivoController@fotoMultas_filtrarBusqueda');

            });

        });

        Route::group(['prefix' => 'mandamientos', 'middleware' => 'necesitaRoles:Administrador|Coordinador Coactivo|Auxiliar Coactivo'], function () {

            Route::get('administrar', 'MandamientoPagoController@administrar');

            Route::get('obtenerListadoMandamientos/{page?}', 'MandamientoPagoController@mandamientoPago_obtenerListado');

            Route::get('obtenerMandamientos/{comparendoNumero}', 'MandamientoPagoController@mandamientoPago_obtenerMandamientos');

            Route::get('nuevoMandamiento', 'MandamientoPagoController@mandamientoPago_nuevo')->middleware('necesitaPermisos:mandamiento-crear');

            Route::post('crearMandamiento', 'MandamientoPagoController@mandamientoPago_crear')->middleware('necesitaPermisos:mandamiento-crear');

            Route::get('editarMandamiento/{id}', 'MandamientoPagoController@mandamientoPago_editar')->middleware('necesitaPermisos:mandamiento-editar');

            Route::post('actualizarMandamiento', 'MandamientoPagoController@mandamientoPago_actualizar')->middleware('necesitaPermisos:mandamiento-editar');

            Route::get('obtenerNoticacionMedio/{page?}', 'MandamientoPagoController@noticacionMedio_obtener');

            Route::get('nuevoNoticacionMedio', 'MandamientoPagoController@noticacionMedio_nuevo')->middleware('necesitaPermisos:mandamiento-crear-notificacion-medio');

            Route::post('crearNoticacionMedio', 'MandamientoPagoController@noticacionMedio_crear')->middleware('necesitaPermisos:mandamiento-crear-notificacion-medio');

            Route::get('editarNoticacionMedio/{id}', 'MandamientoPagoController@noticacionMedio_editar')->middleware('necesitaPermisos:mandamiento-actualizar-notificacion-medio');

            Route::post('actualizarNoticacionMedio', 'MandamientoPagoController@noticacionMedio_actualizar')->middleware('necesitaPermisos:mandamiento-actualizar-notificacion-medio');

            Route::get('obtenerListadoNotificaciones/{mandamientoId}', 'MandamientoPagoController@mandamientoNotificaciones_obtenerListado');

            Route::get('nuevaNotificacion/{mandamientoId}', 'MandamientoPagoController@mandamientoNotificaciones_nueva')->middleware('necesitaPermisos:mandamiento-crear-notificacion');
            
            Route::post('crearNotificacion', 'MandamientoPagoController@mandamientoNotificaciones_crear')->middleware('necesitaPermisos:mandamiento-crear-notificacion');

            Route::get('editarNotificacion/{id}', 'MandamientoPagoController@mandamientoNotificaciones_editar')->middleware('necesitaPermisos:mandamiento-editar-notificacion');

            Route::post('actualizarNotificacion', 'MandamientoPagoController@mandamientoNotificaciones_actualizar')->middleware('necesitaPermisos:mandamiento-editar-notificacion');

            Route::get('nuevaDevolucion/{notificacionId}', 'MandamientoPagoController@devolucionNotificacion_nueva')->middleware('necesitaPermisos:mandamiento-crear-devolucion');

            Route::post('crearDevolucion', 'MandamientoPagoController@devolucionNotificacion_crear')->middleware('necesitaPermisos:mandamiento-crear-devolucion');

            Route::get('editarDevolucion/{id}', 'MandamientoPagoController@devolucionNotificacion_editar')->middleware('necesitaPermisos:mandamiento-editar-devolucion');

            Route::post('actualizarDevolucion', 'MandamientoPagoController@devolucionNotificacion_actualizar')->middleware('necesitaPermisos:mandamiento-editar-devolucion');

            Route::get('obtenerDevolucion/{notificacionId}', 'MandamientoPagoController@devolucionNotificacion_obtener');

            Route::get('obtenerListadoMotivosDevolucion', 'MandamientoPagoController@motivosDevolucion_obtenerListado');

            Route::get('nuevoMotivoDevolucion', 'MandamientoPagoController@motivosDevolucion_nuevo')->middleware('necesitaPermisos:mandamiento-crear-motivo-devolucion');

            Route::post('crearMotivoDevolucion', 'MandamientoPagoController@motivosDevolucion_crear')->middleware('necesitaPermisos:mandamiento-crear-motivo-devolucion');

            Route::get('editarMotivoDevolucion/{id}', 'MandamientoPagoController@motivosDevolucion_editar')->middleware('necesitaPermisos:mandamiento-editar-motivo-devolucion');

            Route::post('actualizarMotivoDevolucion', 'MandamientoPagoController@motivosDevolucion_actualizar')->middleware('necesitaPermisos:mandamiento-editar-motivo-devolucion');

            Route::get('obtenerListadoTiposFinalizacion', 'MandamientoPagoController@tiposFinalizacion_obtenerListado');

            Route::get('nuevoTipoFinalizacion', 'MandamientoPagoController@tiposFinalizacion_nuevo')->middleware('necesitaPermisos:mandamiento-crear-tipo-finalizacion');

            Route::post('crearTipoFinalizacion', 'MandamientoPagoController@tiposFinalizacion_crear')->middleware('necesitaPermisos:mandamiento-crear-tipo-finalizacion');

            Route::get('editarTipoFinalizacion/{id}', 'MandamientoPagoController@tiposFinalizacion_editar')->middleware('necesitaPermisos:mandamiento-editar-tipo-finalizacion');

            Route::post('actualizarTipoFinalizacion', 'MandamientoPagoController@tiposFinalizacion_actualizar')->middleware('necesitaPermisos:mandamiento-editar-tipo-finalizacion'); 
            
            Route::get('obtenerFinalizacion/{id}', 'MandamientoPagoController@mandamientoFinalizacion_obtener');
            
            Route::get('nuevaFinalizacion/{id}', 'MandamientoPagoController@mandamientoFinalizacion_nuevo')->middleware('necesitaPermisos:mandamiento-crear-finalizacion');

            Route::post('crearFinalizacion', 'MandamientoPagoController@mandamientoFinalizacion_crear')->middleware('necesitaPermisos:mandamiento-crear-finalizacion');

            Route::get('editarFinalizacion/{id}', 'MandamientoPagoController@mandamientoFinalizacion_editar')->middleware('necesitaPermisos:mandamiento-editar-finalizacion');

            Route::post('actualizarFinalizacion', 'MandamientoPagoController@mandamientoFinalizacion_actualizar')->middleware('necesitaPermisos:mandamiento-editar-finalizacion');  

            Route::get('obtenerEntrega/{id}', 'MandamientoPagoController@entregaNotificacion_obtener');
            
            Route::get('nuevaEntrega/{id}', 'MandamientoPagoController@entregaNotificacion_nuevo')->middleware('necesitaPermisos:mandamiento-crear-entrega');

            Route::post('crearEntrega', 'MandamientoPagoController@entregaNotificacion_crear')->middleware('necesitaPermisos:mandamiento-crear-entrega');

            Route::get('editarEntrega/{id}', 'MandamientoPagoController@entregaNotificacion_editar')->middleware('necesitaPermisos:mandamiento-actualizar-entrega');

            Route::post('actualizarEntrega', 'MandamientoPagoController@entregaNotificacion_actualizar')->middleware('necesitaPermisos:mandamiento-actualizar-entrega');

            Route::get('obtenerListadoTiposNotificacion', 'MandamientoPagoController@tiposNotificacion_obtenerListado');

            Route::get('nuevoTipoNotificacion', 'MandamientoPagoController@tiposNotificacion_nuevo')->middleware('necesitaPermisos:mandamiento-crear--tipo-notificacion');

            Route::post('crearTipoNotificacion', 'MandamientoPagoController@tiposNotificacion_crear')->middleware('necesitaPermisos:mandamiento-crear--tipo-notificacion');

            Route::get('editarTipoNotificacion/{id}', 'MandamientoPagoController@tiposNotificacion_editar')->middleware('necesitaPermisos:mandamiento-editar-tipo-notificacion');

            Route::post('actualizarTipoNotificacion', 'MandamientoPagoController@tiposNotificacion_actualizar')->middleware('necesitaPermisos:mandamiento-editar-tipo-notificacion');

            Route::get('obtenerListadoMediosNotificacion', 'MandamientoPagoController@mediosNotificacion_obtenerListado');

            Route::get('nuevoMedioNotificacion', 'MandamientoPagoController@mediosNotificacion_nuevo')->middleware('necesitaPermisos:mandamiento-crear-medio-notificacion');

            Route::post('crearMedioNotificacion', 'MandamientoPagoController@mediosNotificacion_crear')->middleware('necesitaPermisos:mandamiento-crear-medio-notificacion');

            Route::get('editarMedioNotificacion/{id}', 'MandamientoPagoController@mediosNotificacion_editar')->middleware('necesitaPermisos:mandamiento-editar-medio-notificacion');

            Route::post('actualizarMedioNotificacion', 'MandamientoPagoController@mediosNotificacion_actualizar')->middleware('necesitaPermisos:mandamiento-editar-medio-notificacion');

            Route::get('obtenerDocumentoMandamiento/{id}', 'MandamientoPagoController@obtenerDocumentoMandamiento');

            Route::get('obtenerDocumentoSancion/{id}', 'MandamientoPagoController@obtenerDocumentoSancion');

            Route::get('obtenerMandamientoNotificacion/{id}', 'MandamientoPagoController@obtenerMandamientoNotificacion');

            Route::get('obtenerPantalalzoRuntMandamientoNotificacion/{id}', 'MandamientoPagoController@obtenerPantalalzoRuntMandamientoNotificacion');

            Route::get('verComparendo/{id}', 'MandamientoPagoController@verComparendo');

            Route::get('obtenerDocumentoFinalizacion/{id}', 'MandamientoPagoController@mandamientoFinalizacion_obtenerDocumento');

            Route::get('verSancion/{id}', 'MandamientoPagoController@verSancion');

            Route::get('obtenerDocumentoEntrega/{id}', 'MandamientoPagoController@obtenerDocumentoEntrega');

            Route::get('verAcuerdoPago/{id}', 'MandamientoPagoController@verAcuerdoPago');

            Route::get('obtenerDocumentoAcuerdoPago/{id}', 'MandamientoPagoController@obtenerDocumentoAcuerdoPago');

            Route::get('verProcesoAcuerdoPago/{id}', 'MandamientoPagoController@verProcesoAcuerdoPago');

            Route::get('registrarPago/{id}', 'MandamientoPagoController@realizarPago')->middleware('necesitaPermisos:mandamiento-registrar-pago');

            Route::post('registrarPago', 'MandamientoPagoController@registrarPago')->middleware('necesitaPermisos:mandamiento-registrar-pago');

            Route::get('verPago/{id}', 'MandamientoPagoController@verPago');

            Route::get('editarPago/{id}', 'MandamientoPagoController@editarPago')->middleware('necesitaPermisos:mandamiento-editar-pago');

            Route::post('editarPago', 'MandamientoPagoController@actualizarPago')->middleware('necesitaPermisos:mandamiento-editar-pago');

            Route::get('obtenerConsginacionPago/{id}', 'MandamientoPagoController@obtenerPagoConsignacion');

            Route::get('filtrar/{parametros}/{valor}/{page?}', 'MandamientoPagoController@filtrarMandamientos');

        });

    });
    /*
     * Gestion documental: PQR
     */
    Route::group(['prefix' => 'pqr', 'middleware' => 'necesitaRoles:Administrador|Administrador PQR'], function () {

        Route::get('obtenerAllCoEx/{page?}', 'PQRController@obtenerAllCoEx');

        Route::get('obtenerAllCoIn/{page?}', 'PQRController@obtenerAllCoIn');

        Route::get('obtenerAllCoSa/{page?}', 'PQRController@obtenerAllCoSa');

        Route::get('administrar', 'PQRController@administrar');

        Route::get('asignar/{id}', 'PQRController@asignar')->middleware('necesitaPermisos:pqr-asignar');

        Route::post('asignar', 'PQRController@registrarAsignacion')->middleware('necesitaPermisos:pqr-asignar');

        Route::get('reAsignar/{id}', 'PQRController@reAsignar')->middleware('necesitaPermisos:pqr-reasignar');

        Route::post('reAsignar', 'PQRController@registrarReAsignacion')->middleware('necesitaPermisos:pqr-reasignar');

        Route::get('historialAsignaciones/{id}', 'PQRController@historialAsignaciones');

        Route::get('verRespuesta/{id}', 'PQRController@verRespuesta');

        Route::get('verAsunto/{id}', 'PQRController@verAsunto');

        Route::get('respuesta/get/anexos/{id}', 'PQRController@getAnexos');

        Route::get('respuesta/get/documento/{id}', 'PQRController@getDocumento');

        Route::group(['prefix' => 'calendario'], function () {

            Route::get('administrar', 'CalendarioController@administrar')->middleware('necesitaPermisos:administrar-calendario');

            Route::post('importar', 'CalendarioController@importar')->middleware('necesitaPermisos:importar-registros-calendario');
        });

        Route::group(['prefix' => 'filtrar'], function () {

            Route::get('CoEx/{parametro}/{filtro}/{page?}', 'PQRController@filtrarCoEx');

            Route::get('CoSa/{parametro}/{filtro}/{page?}', 'PQRController@filtrarCoSa');

            Route::get('CoIn/{parametro}/{filtro}/{page?}', 'PQRController@filtrarCoIn');

        });

        Route::get('clasificar/{id}', 'PQRController@clasificar')->middleware('necesitaPermisos:pqr-clasificar');

        Route::post('clasificar', 'PQRController@registrarClasificacion')->middleware('necesitaPermisos:pqr-clasificar');

        Route::get('verClasificacion/{id_clasificacion}/{id_pqr}', 'PQRController@verClasificacion');

        Route::post('editarClasificacion', 'PQRController@editarClasificacion')->middleware('necesitaPermisos:pqr-editar-clasificacion');

        Route::get('generarRadicadoSalida/{id}', 'PQRController@radicadoSalida')->middleware('necesitaPermisos:pqr-crear');

        Route::get('generarRadicadoEntrada/{id}', 'PQRController@radicadoEntrada')->middleware('necesitaPermisos:pqr-crear');

        Route::get('responder/{id_pqr}/{id_asignacion}', 'PQRController@responder');

        Route::post('responder', 'PQRController@registrarRespuesta')->middleware('necesitaPermisos:pqr-responder');

        Route::get('anexos/{id}', 'PQRController@obtenerAnexos');

        Route::get('cargarClases/{page?}', 'PQRController@cargarClases');

        Route::get('cargarClase/{id}', 'PQRController@cargarClase')->middleware('necesitaPermisos:pqr-modificar-clase');

        Route::get('nuevaClase', 'PQRController@nuevaClase')->middleware('necesitaPermisos:pqr-crear-clase');

        Route::post('crearClase', 'PQRController@crearClase')->middleware('necesitaPermisos:pqr-crear-clase');

        Route::post('modificarClase', 'PQRController@modificarClase')->middleware('necesitaPermisos:pqr-modificar-clase');

        Route::get('eliminarClase/{id}', 'PQRController@eliminarClase')->middleware('necesitaPermisos:pqr-eliminar-clase');

        Route::get('restaurarClase/{id}', 'PQRController@restaurarClase')->middleware('necesitaPermisos:pqr-restaurar-clase');

        Route::get('cargarMediosTraslado/{page?}', 'PQRController@cargarMediosTraslado');

        Route::get('cargarMedio/{id}', 'PQRController@cargarMedio')->middleware('necesitaPermisos:pqr-modificar-medio');

        Route::get('nuevoMedio', 'PQRController@nuevoMedio')->middleware('necesitaPermisos:pqr-crear-medio');

        Route::post('crearMedio', 'PQRController@crearMedio')->middleware('necesitaPermisos:pqr-crear-medio');

        Route::post('modificarMedio', 'PQRController@modificarMedio')->middleware('necesitaPermisos:pqr-modificar-medio');

        Route::get('eliminarMedio/{id}', 'PQRController@eliminarMedio')->middleware('necesitaPermisos:pqr-eliminar-medio');

        Route::get('restaurarMedio/{id}', 'PQRController@restaurarMedio')->middleware('necesitaPermisos:pqr-restaurar-medio');

        Route::post('nuevoCoExAd', 'PQRController@crearCoExAd')->middleware('necesitaPermisos:pqr-crear');

        Route::get('pdf/{id}', 'PQRController@getPDF');

        Route::post('nuevoCoIn', 'PQRController@crearCoIn')->middleware('necesitaPermisos:pqr-crear');

        Route::post('nuevoCoSa', 'PQRController@crearCoSa')->middleware('necesitaPermisos:pqr-crear');

        Route::get('verEntrega/{id}', 'PQRController@verEntrega');

        Route::get('cosa/getDoEn/{id}', 'PQRController@obtenerDoEn');

        Route::get('crearCoEx', 'PQRController@nuevoCoEx')->middleware('necesitaPermisos:pqr-crear');

        Route::get('crearCoIn', 'PQRController@nuevoCoIn')->middleware('necesitaPermisos:pqr-crear');

        Route::get('crearCoSa', 'PQRController@nuevoCoSa')->middleware('necesitaPermisos:pqr-crear');

        Route::post('uploadFileRadicado', 'PQRController@uploadFileRadicado')->middleware('necesitaPermisos:pqr-crear');

        Route::get('obtenerRadicado/{tipo}/{id}', 'PQRController@obtenerRadicado');

        Route::get('documentoRadicado/{id}', 'PQRController@obtenerDoRa');

        Route::get('cargarModalidades/{page?}', 'PQRController@cargarModalidades');

        Route::get('nuevaModalidad', 'PQRController@nuevaModalidad')->middleware('necesitaPermisos:pqr-crear-modalidad');

        Route::post('crearModalidad', 'PQRController@crearModalidad')->middleware('necesitaPermisos:pqr-crear-modalidad');

        Route::get('editarModalidad/{id}', 'PQRController@editarModalidad')->middleware('necesitaPermisos:pqr-editar-modalidad');

        Route::post('actualizarModalidad', 'PQRController@actualizarModalidad')->middleware('necesitaPermisos:pqr-editar-modalidad');

        Route::get('eliminarModalidad/{id}', 'PQRController@eliminarModalidad')->middleware('necesitaPermisos:pqr-eliminar-modalidad');

        Route::get('restaurarModalidad/{id}', 'PQRController@restaurarModalidad')->middleware('necesitaPermisos:pqr-restaurar-modalidad');

        Route::get('cambiarFechaLimite/{id}', 'PQRController@cambiarFechaLimite')->middleware('necesitaPermisos:pqr-crear');

        Route::post('cambiarFechaLimite', 'PQRController@actualizarFechaLimite')->middleware('necesitaPermisos:pqr-crear');

        Route::get('reUploadFileRadicado/{id}', 'PQRController@reUploadFileRadicadoE1')->middleware('necesitaPermisos:pqr-crear');

        Route::post('reUploadFileRadicado', 'PQRController@reUploadFileRadicadoE2')->middleware('necesitaPermisos:pqr-crear');

        Route::get('reGenerarPDF/{id}', 'PQRController@reGenerarPDF')->middleware('necesitaPermisos:pqr-crear');

        Route::get('vincularRadicadosEntrada/{id}','PQRController@vincularRadicadosEntradaF1')->middleware('necesitaPermisos:pqr-crear');

        Route::post('vincularRadicadosEntrada','PQRController@vincularRadicadosEntradaF2')->middleware('necesitaPermisos:pqr-crear');

        Route::get('cambiarClase/{id}', 'PQRController@cambiarClase')->middleware('necesitaPermisos:pqr-cambiar-clase');

        Route::post('cambiarClase', 'PQRController@registrarCambioClase')->middleware('necesitaPermisos:pqr-cambiar-clase');

        Route::get('cambiarFuncionario/{pqrId}/{funcionarioId}', 'PQRController@cambiarFuncionarioF1')->middleware('necesitaPermisos:pqr-cambiar-funcionario');

        Route::post('cambiarFuncionario', 'PQRController@cambiarFuncionarioF2')->middleware('necesitaPermisos:pqr-cambiar-funcionario');

        Route::get('modificarRadicadoRespuesta/{pqrId}/{radicado}', function ($pqrId, $radicado){
            return view('admin.gestion_documental.pqr.editarRadicadoRespuesta',['pqrId'=>$pqrId,'radicado'=>$radicado])->render();
        })->middleware('necesitaPermisos:pqr-modificar-radicado-contestacion');

        Route::post('modificarRadicadoRespuesta', 'PQRController@modificarRadicadoContestacion')->middleware('necesitaPermisos:pqr-modificar-radicado-contestacion');

        Route::get('desvincularRadicado/{pqrId}/{radicado}', 'PQRController@eliminarRadicadoContestacion')->middleware('necesitaPermisos:pqr-eliminar-radicado-contestacion');

        Route::get('cambiarMedioTraslado/{id}', 'PQRController@cambiarMedioTraslado')->middleware('necesitaPermisos:pqr-cambiar-medio-traslado');

        Route::post('cambiarMedioTraslado', 'PQRController@registrarCambioMedioTraslado')->middleware('necesitaPermisos:pqr-cambiar-medio-traslado');

        Route::get('anular/{id}','PQRController@anularProceso')->middleware('necesitaPermisos:pqr-anular');

        Route::post('anular', 'PQRController@registrarAnulacionProceso')->middleware('necesitaPermisos:pqr-anular');

        Route::get('obtenerMotivosAnulacion/{page?}', 'PQRController@obtenerMotivosAnulacion');

        Route::get('nuevoMotivoAnulacion', 'PQRController@nuevoMotivoAnulacion')->middleware('necesitaPermisos:pqr-crear-motivo-anulacion');

        Route::post('nuevoMotivoAnulacion', 'PQRController@crearMotivoAnulacion')->middleware('necesitaPermisos:pqr-crear-motivo-anulacion');

        Route::get('editarMotivoAnulacion/{id}', 'PQRController@editarMotivoAnulacion')->middleware('necesitaPermisos:pqr-editar-motivo-anulacion');

        Route::post('editarMotivoAnulacion', 'PQRController@actualizarMotivoAnulacion')->middleware('necesitaPermisos:pqr-editar-motivo-anulacion');

        Route::get('verAnulacion/{id}','PQRController@verAnulacion');

    });

    Route::group(['prefix' => 'mis-pqr'], function (){

        Route::group(['prefix' => 'filtrar'], function () {

            Route::get('CoEx/{parametro}/{filtro}/{page?}', 'PQRController@filtrarMisProcesosCoEx');

            Route::get('CoSa/{parametro}/{filtro}/{page?}', 'PQRController@filtrarMisProcesosCoSa');

            Route::get('CoIn/{parametro}/{filtro}/{page?}', 'PQRController@filtrarMisProcesosCoIn');

        });

        Route::get('misProcesos', 'PQRController@misProcesos');

        Route::group(['prefix' => 'filtrar'], function () {

            Route::get('MisCoEx/{parametro}/{filtro}/{page?}', 'PQRController@filtrarMisCoEx');

            Route::get('MisCoSa/{parametro}/{filtro}/{page?}', 'PQRController@filtrarMisCoSa');

            Route::get('MisCoIn/{parametro}/{filtro}/{page?}', 'PQRController@filtrarMisCoIn');

        });

        Route::get('documentoRadicado/{id}', 'PQRController@obtenerDoRa');

        Route::get('pdf/{id}', 'PQRController@getPDF');

        Route::get('anexos/{id}', 'PQRController@obtenerAnexos');

        Route::get('verClasificacion/{id_clasificacion}/{id_pqr}', 'PQRController@verClasificacion');

        Route::get('historialAsignaciones/{id}', 'PQRController@historialAsignaciones');

        Route::get('verRespuesta/{id}', 'PQRController@verRespuesta');

        Route::get('verAsunto/{id}', 'PQRController@verAsunto');

        Route::get('respuesta/get/anexos/{id}', 'PQRController@getAnexos');

        Route::get('respuesta/get/documento/{id}', 'PQRController@getDocumento');

        Route::get('obtenerMisProcesosCoEx/{page?}', 'PQRController@obtenerMisProcesosCoEx');

        Route::get('obtenerMisProcesosCoIn/{page?}', 'PQRController@obtenerMisProcesosCoIn');

        Route::get('obtenerMisProcesosCoSa/{page?}', 'PQRController@obtenerMisProcesosCoSa');

        Route::get('registrarEnvio/{id}','PQRController@registrarEnvio');

        Route::post('registrarEnvio','PQRController@guardarEnvio');

        Route::post('registrarEntrega', 'PQRController@registrarEntrega');

        Route::get('registrarEntrega/{id}', 'PQRController@realizarEntrega');

        Route::get('verEntrega/{id}', 'PQRController@verEntrega');

        Route::get('cosa/getDoEn/{id}', 'PQRController@obtenerDoEn');

        Route::get('verEnvio/{id}', 'PQRController@verEnvio');

        Route::get('verPeticionario/{id}', 'PQRController@verPeticionario');

    });
    /*
     * Gestion documental: Tablas de rentencion documental (TRD)
     */
    Route::group(['prefix' => 'trd', 'middleware' => 'necesitaRoles:Administrador|Administrador TRD'], function () {

        Route::get('administrar', 'TRDController@administrar');

        Route::get('obtenerSeries/{format}', 'TRDController@obtenerSeries');

        Route::get('obtenerSubSeries/{serie_id}/{format}', 'TRDController@obtenerSubSeries');

        Route::get('obtenerTiposDocumentos/{sub_serie_id}/{format}', 'TRDController@obtenerTiposDocumentos');

        Route::get('obtenerSerie/{id}', 'TRDController@obtenerSerie');

        Route::get('obtenerSubSerie/{id}', 'TRDController@obtenerSubSerie');

        Route::get('obtenerTipoDocumento/{id}', 'TRDController@obtenerTipoDocumento');

        Route::get('crearSerie', 'TRDController@nuevaSerie')->middleware('necesitaPermisos:trd-crear-serie');

        Route::post('crearSerie', 'TRDController@crearSerie')->middleware('necesitaPermisos:trd-crear-serie');

        Route::get('crearSubSerie', 'TRDController@nuevaSubSerie')->middleware('necesitaPermisos:trd-crear-sub-serie');

        Route::post('crearSubSerie', 'TRDController@crearSubSerie')->middleware('necesitaPermisos:trd-crear-sub-serie');

        Route::get('crearTipoDocumento', 'TRDController@nuevoTipoDocumento')->middleware('necesitaPermisos:trd-crear-tipo-documento');

        Route::post('crearTipoDocumento', 'TRDController@crearTipoDocumento')->middleware('necesitaPermisos:trd-crear-tipo-documento');

        Route::get('editarSerie/{id}', 'TRDController@obtenerSerie')->middleware('necesitaPermisos:trd-editar-serie');

        Route::post('editarSerie', 'TRDController@editarSerie')->middleware('necesitaPermisos:trd-editar-serie');

        Route::get('editarSubSerie/{id}', 'TRDController@obtenerSubSerie')->middleware('necesitaPermisos:trd-editar-sub-serie');

        Route::post('editarSubSerie', 'TRDController@editarSubSerie')->middleware('necesitaPermisos:trd-editar-sub-serie');

        Route::get('editarTipoDocumento/{id}', 'TRDController@obtenerTipoDocumento')->middleware('necesitaPermisos:trd-editar-tipo-documento');

        Route::post('editarTipoDocumento', 'TRDController@editarTipoDocumento')->middleware('necesitaPermisos:trd-editar-tipo-documento');

        Route::get('eliminarTipoDocumento/{id}', 'TRDController@eliminarTipo')->middleware('necesitaPermisos:trd-eliminar-tipo-documento');

        Route::get('eliminarSubSerie/{id}', 'TRDController@eliminarSubSerie')->middleware('necesitaPermisos:trd-eliminar-sub-serie');

        Route::get('eliminarSerie/{id}', 'TRDController@eliminarSerie')->middleware('necesitaPermisos:trd-eliminar-serie');

    });

    Route::group(['prefix' => 'notificacionesAviso'], function () {

        Route::get('administrar', 'NotificacionAvisoController@administrar');

        Route::get('nueva', 'NotificacionAvisoController@nueva')->middleware('necesitaPermisos:notificacion-aviso-crear');

        Route::post('nueva', 'NotificacionAvisoController@crear')->middleware('necesitaPermisos:notificacion-aviso-crear');

        Route::get('obtenerTodas', 'NotificacionAvisoController@obtenerTodas');

        Route::get('obtenerDocumento/{id}', 'NotificacionAvisoController@obtenerDocumento');

        Route::get('editarNotificacionAviso/{id}', 'NotificacionAvisoController@editarNotificacionAviso')->middleware('necesitaPermisos:notificacion-aviso-editar');

        Route::post('editarNotificacionAviso', 'NotificacionAvisoController@actualizarNotificacionAviso')->middleware('necesitaPermisos:notificacion-aviso-editar');

        Route::get('filtrar/{criterio}/{parametro}', 'NotificacionAvisoController@filtrar');

        Route::get('eliminarNotificacionAviso/{id}', 'NotificacionAvisoController@eliminarNotificacionAviso')->middleware('necesitaPermisos:notificacion-aviso-eliminar');

        Route::get('obtenerListadoTiposNotificacionesAviso/{page?}', 'NotificacionAvisoController@obtenerListadoTiposNotificacionesAviso');

        Route::get('nuevoTipoNotificacionAviso', 'NotificacionAvisoController@nuevoTipoNotificacionAviso')->middleware('necesitaPermisos:notificacion-aviso-crear-tipo');

        Route::post('nuevoTipoNotificacionAviso', 'NotificacionAvisoController@crearTipoNotificacionAviso')->middleware('necesitaPermisos:notificacion-aviso-crear-tipo');

        Route::get('editarTipoNotificacionAviso/{id}', 'NotificacionAvisoController@editarTipoNotificacionAviso')->middleware('necesitaPermisos:notificacion-aviso-editar-tipo');

        Route::post('editarTipoNotificacionAviso', 'NotificacionAvisoController@actualizarTipoNotificacionAviso')->middleware('necesitaPermisos:notificacion-aviso-editar-tipo');

    });

    Route::group(['prefix' => 'normativa'], function () {

        Route::get('administrar', 'NormativaController@administrar');

        Route::get('nueva', 'NormativaController@nueva')->middleware('necesitaPermisos:normativa-crear');

        Route::post('nueva', 'NormativaController@crear')->middleware('necesitaPermisos:normativa-crear');

        Route::get('obtenerTodas', 'NormativaController@obtenerTodas');

        Route::get('obtenerDocumento/{id}', 'NormativaController@obtenerDocumento');

        Route::get('editarNormativa/{id}', 'NormativaController@editar')->middleware('necesitaPermisos:normativa-editar');

        Route::post('editarNormativa', 'NormativaController@actualizar')->middleware('necesitaPermisos:normativa-editar');

        Route::get('filtrar/{criterio}/{parametro}', 'NormativaController@filtrar');

        Route::get('obtenerListadoTiposNormativa/{page?}', 'NormativaController@obtenerListadoTiposNormativa');

        Route::get('nuevoTipoNormativa', 'NormativaController@nuevoTipoNormativa')->middleware('necesitaPermisos:normativa-crear-tipo');

        Route::post('nuevoTipoNormativa', 'NormativaController@crearTipoNormativa')->middleware('necesitaPermisos:normativa-crear-tipo');

        Route::get('editarTipoNormativa/{id}', 'NormativaController@editarTipoNormativa')->middleware('necesitaPermisos:normativa-editar-tipo');

        Route::post('editarTipoNormativa', 'NormativaController@actualizarTipoNormativa')->middleware('necesitaPermisos:normativa-editar-tipo');

    });
    /*
     * Sistema - Administración
     */
    Route::group(['prefix' => 'sistema', 'middleware' => ['auth', 'necesitaRoles:Administrador|Auxiliar Trámites']], function () {

        Route::group(['prefix' => 'parametros', 'middleware' => 'necesitaPermisos:administrar-parametros'], function () {

            Route::group(['prefix' => 'empresa'], function () {

                Route::get('administrar', 'SistemaParametrosController@empresa_administrar');

                Route::get('obtenerRegistros/{page?}', 'SistemaParametrosController@empresa_obtenerRegistros');

                Route::get('nuevoRegistro', 'SistemaParametrosController@empresa_nuevoRegistro')->middleware('necesitaPermisos:parametro-crear-empresa');

                Route::post('crearRegistro', 'SistemaParametrosController@empresa_crearRegistro')->middleware('necesitaPermisos:parametro-crear-empresa');

                Route::get('editarRegistro/{id}', 'SistemaParametrosController@empresa_editarRegistro')->middleware('necesitaPermisos:parametro-editar-empresa');

                Route::post('guardarCambios', 'SistemaParametrosController@empresa_guardarCambios')->middleware('necesitaPermisos:parametro-editar-empresa');

                Route::post('filtrarRegistros', 'SistemaParametrosController@empresa_filtrarRegistros');

                Route::get('obtenerFirma/{id}','SistemaParametrosController@empresa_obtenerFirma');

                Route::get('obtenerFirmaInspector/{id}','SistemaParametrosController@empresa_obtenerFirmaInspector');

            });

            Route::group(['prefix' => 'pqr'], function () {

                Route::get('administrar', 'SistemaParametrosController@pqr_administrar');

                Route::get('obtenerRegistros/{page?}', 'SistemaParametrosController@pqr_obtenerRegistros');

                Route::get('nuevoRegistro', 'SistemaParametrosController@pqr_nuevoRegistro')->middleware('necesitaPermisos:parametro-crear-pqr');

                Route::post('crearRegistro', 'SistemaParametrosController@pqr_crearRegistro')->middleware('necesitaPermisos:parametro-crear-pqr');

                Route::get('editarRegistro/{id}', 'SistemaParametrosController@pqr_editarRegistro')->middleware('necesitaPermisos:parametro-editar-pqr');

                Route::post('guardarCambios', 'SistemaParametrosController@pqr_guardarCambios')->middleware('necesitaPermisos:parametro-editar-pqr');

                Route::post('filtrarRegistros', 'SistemaParametrosController@pqr_filtrarRegistros');

            });

            Route::group(['prefix' => 'tramites'], function () {

                Route::get('administrar', 'SistemaParametrosController@tramites_administrar');

                Route::get('obtenerRegistros/{page?}', 'SistemaParametrosController@tramites_obtenerRegistros');

                Route::get('nuevoRegistro', 'SistemaParametrosController@tramites_nuevoRegistro')->middleware('necesitaPermisos:parametro-tramite-crear');

                Route::post('crearRegistro', 'SistemaParametrosController@tramites_crearRegistro')->middleware('necesitaPermisos:parametro-tramite-crear');

                Route::get('editarRegistro/{id}', 'SistemaParametrosController@tramites_editarRegistro')->middleware('necesitaPermisos:parametro-tramite-editar');

                Route::post('guardarCambios', 'SistemaParametrosController@tramites_guardarCambios')->middleware('necesitaPermisos:parametro-tramite-editar');

                Route::post('filtrarRegistros', 'SistemaParametrosController@tramites_filtrarRegistros');

            });

            Route::group(['prefix' => 'vigencias'], function () {

                Route::get('administrar', 'SistemaParametrosController@vigencias_administrar');

                Route::get('obtenerRegistros/{page?}', 'SistemaParametrosController@vigencias_obtenerRegistros');

                Route::get('nuevoRegistro', 'SistemaParametrosController@vigencias_nuevoRegistro')->middleware('necesitaPermisos:parametro-crear-vigencia');

                Route::post('crearRegistro', 'SistemaParametrosController@vigencias_crearRegistro')->middleware('necesitaPermisos:parametro-crear-vigencia');

                Route::get('editarRegistro/{id}', 'SistemaParametrosController@vigencias_editarRegistro')->middleware('necesitaPermisos:parametro-editar-vigencia');

                Route::post('guardarCambios', 'SistemaParametrosController@vigencias_guardarCambios')->middleware('necesitaPermisos:parametro-editar-vigencia');

                Route::post('filtrarRegistros', 'SistemaParametrosController@vigencias_filtrarRegistros');

            });

            Route::group(['prefix' => 'gestionDocumental'], function () {

                Route::get('administrar', 'SistemaParametrosController@gd_administrar');

                Route::get('obtenerRegistros/{page?}', 'SistemaParametrosController@gd_obtenerRegistros');

                Route::get('nuevoRegistro', 'SistemaParametrosController@gd_nuevoRegistro')->middleware('necesitaPermisos:parametro-crear-gd');

                Route::post('crearRegistro', 'SistemaParametrosController@gd_crearRegistro')->middleware('necesitaPermisos:parametro-crear-gd');

                Route::get('editarRegistro/{id}', 'SistemaParametrosController@gd_editarRegistro')->middleware('necesitaPermisos:parametro-editar-gd');

                Route::post('guardarCambios', 'SistemaParametrosController@gd_guardarCambios')->middleware('necesitaPermisos:parametro-editar-gd');

                Route::post('filtrarRegistros', 'SistemaParametrosController@gd_filtrarRegistros');

            });

            Route::group(['prefix' => 'to'], function () {

                Route::get('administrar', 'SistemaParametrosController@to_administrar');

                Route::get('obtenerRegistros/{page?}', 'SistemaParametrosController@to_obtenerRegistros');

                Route::get('nuevoRegistro', 'SistemaParametrosController@to_nuevoRegistro')->middleware('necesitaPermisos:parametro-to-crear');

                Route::post('crearRegistro', 'SistemaParametrosController@to_crearRegistro')->middleware('necesitaPermisos:parametro-to-crear');

                Route::get('editarRegistro/{id}', 'SistemaParametrosController@to_editarRegistro')->middleware('necesitaPermisos:parametro-to-editar');

                Route::post('guardarCambios', 'SistemaParametrosController@to_guardarCambios')->middleware('necesitaPermisos:parametro-to-editar');

                Route::post('filtrarRegistros', 'SistemaParametrosController@to_filtrarRegistros');

            });

        });

        Route::group(['prefix' => 'logs', 'middleware' => 'necesitaPermisos:log-monitorear'], function () {

            Route::get('monitor', 'LogController@monitor');

            Route::get('obtenerLogsActividades/{page?}', 'LogController@obtenerLogsActividad');

            Route::get('obtenerLogsExcepciones/{page?}', 'LogController@obtenerLogsExcepciones');

            Route::get('verCambiosActividad/{id}', 'LogController@obtenerCambiosActividad');
        });

        Route::group(['prefix' => 'usuarios', 'middleware' => 'necesitaPermisos:usuario-administrar'], function () {

            Route::get('obtenerUsuarios/{page?}', 'UsuarioController@obtenerUsuarios');

            Route::get('nuevo', 'UsuarioController@nuevo')->middleware('necesitaPermisos:usuario-crear');

            Route::post('crear', 'UsuarioController@crear')->middleware('necesitaPermisos:usuario-crear');

            Route::get('editar/{id}', 'UsuarioController@editar')->middleware('necesitaPermisos:usuario-editar');

            Route::post('editar', 'UsuarioController@actualizarUsuario')->middleware('necesitaPermisos:usuario-editar');

            Route::get('administrar', 'UsuarioController@administrar');

            Route::get('perfil/{usuarioId}', 'UsuarioController@verPerfilUsuario')->middleware('necesitaPermisos:usuario-ver-perfil');

            Route::get('desactivar/{id}', 'UsuarioController@desactivar')->middleware('necesitaPermisos:usuario-desactivar');

            Route::get('activar/{id}', 'UsuarioController@activar')->middleware('necesitaPermisos:usuario-activar');

            Route::get('eliminar/{id}', 'UsuarioController@eliminar')->middleware('necesitaPermisos:usuario-eliminar');

            Route::get('restaurar/{id}', 'UsuarioController@restaurar')->middleware('necesitaPermisos:usuario-restaurar');

            Route::get('convertirEnAgente/{id}', 'UsuarioController@convertirEnAgente')->middleware('necesitaPermisos:usuario-editar');

            Route::post('convertirEnAgente', 'UsuarioController@registrarAgente')->middleware('necesitaPermisos:usuario-editar');

            Route::get('verAgente/{id}', 'UsuarioController@verAgente');

            Route::get('desvincularAgente/{id}', function ($id){
                return view('admin.sistema.usuarios.desvincularAgente', ['id'=>$id]);
            })->middleware('necesitaPermisos:usuario-editar');

            Route::post('desvincularAgente', 'UsuarioController@desvincularAgente')->middleware('necesitaPermisos:usuario-editar');

        });

        Route::group(['prefix' => 'roles', 'middleware' => 'necesitaPermisos:rol-administrar'], function () {

            Route::get('administrar', 'RoleController@administrar');

            Route::get('obtenerRoles/{page?}', 'RoleController@obtenerRoles');

            Route::post('crear', 'RoleController@crear')->middleware('necesitaPermisos:rol-crear');

            Route::get('consultar', 'RoleController@consultar')->middleware('necesitaPermisos:rol-consultar');

            Route::get('editar/{id}', 'RoleController@editar')->middleware('necesitaPermisos:rol-editar');

            Route::post('editar', 'RoleController@guardarCambios')->middleware('necesitaPermisos:rol-editar');

            Route::get('nuevo', 'RoleController@nuevoRol')->middleware('necesitaPermisos:rol-crear');

        });

        Route::group(['prefix' => 'empresasTransporte', 'middleware' => 'necesitaPermisos:empresa-transporte-administrar'], function () {

            Route::get('administrar', 'EmpresaTransporteController@administrar');

            Route::get('editar/{id}', 'EmpresaTransporteController@editarEmpresa')->middleware('necesitaPermisos:empresa-transporte-editar');

            Route::get('obtenerEmpresas/{page?}', 'EmpresaTransporteController@obtenerEmpresas');

            Route::post('editar', 'EmpresaTransporteController@actualizarEmpresa')->middleware('necesitaPermisos:empresa-transporte-editar');

            Route::post('crear', 'EmpresaTransporteController@crearEmpresa')->middleware('necesitaPermisos:empresa-transporte-crear');

            Route::get('nueva', 'EmpresaTransporteController@nuevaEmpresa')->middleware('necesitaPermisos:empresa-transporte-crear');

        });

        Route::group(['prefix' => 'empresasMensajeria', 'middleware' => 'necesitaPermisos:empresa-mensajeria-administrar'], function () {

            Route::get('administrar', 'EmpresaMensajeriaController@administrar');

            Route::get('editar/{id}', 'EmpresaMensajeriaController@editarEmpresa')->middleware('necesitaPermisos:empresa-mensajeria-editar');

            Route::get('obtenerEmpresas/{page?}', 'EmpresaMensajeriaController@obtenerEmpresas');

            Route::post('editar', 'EmpresaMensajeriaController@actualizarEmpresa')->middleware('necesitaPermisos:empresa-mensajeria-editar');

            Route::post('crear', 'EmpresaMensajeriaController@crearEmpresa')->middleware('necesitaPermisos:empresa-mensajeria-crear');

            Route::get('nueva', 'EmpresaMensajeriaController@nuevaEmpresa')->middleware('necesitaPermisos:empresa-mensajeria-crear');

        });

        Route::group(['prefix' => 'dependencias', 'middleware' => 'necesitaPermisos:dependencia-administrar'], function () {

            Route::get('obtenerDependencias/{page?}', 'DependenciaController@obtenerDependencias');

            Route::get('administrar', 'DependenciaController@administrar');

            Route::get('eliminarDependencia/{id}', 'DependenciaController@eliminarDependencia')->middleware('necesitaPermisos:dependencia-eliminar');

            Route::get('restaurarDependencia/{id}', 'DependenciaController@restaurarDependencia')->middleware('necesitaPermisos:dependencia-restaurar');

            Route::get('editarDependencia/{id}', 'DependenciaController@editarDependencia')->middleware('necesitaPermisos:dependencia-editar');

            Route::post('crearDependencia', 'DependenciaController@crearDependencia')->middleware('necesitaPermisos:dependencia-crear');

            Route::post('actualizarDependencia', 'DependenciaController@actualizarDependencia')->middleware('necesitaPermisos:dependencia-editar');

            Route::get('obtenerFuncionarios/{id}', 'DependenciaController@obtenerFuncionariosDependencia');

            Route::get('nuevaDependencia', 'DependenciaController@nuevaDependencia')->middleware('necesitaPermisos:dependencia-crear');

        });

        Route::group(['prefix' => 'calendario', 'middleware' => 'necesitaPermisos:calendario-administrar'], function () {

            Route::get('administrar', 'CalendarioController@administrar');

            Route::get('obtenerRegistros/{year?}/{month?}', 'CalendarioController@obtenerRegistros');

            Route::post('importar', 'CalendarioController@importar')->middleware('necesitaPermisos:calendario-importar-registros');

        });

        Route::group(['prefix' => 'documentosIdentidad', 'middleware' => 'necesitaPermisos:documentos-identidad-administrar'], function () {

            Route::get('administrar', 'DocumentoIdentidadController@administrar');

            Route::get('nuevo', 'DocumentoIdentidadController@nuevo')->middleware('necesitaPermisos:documentos-identidad-crear');

            Route::post('crear', 'DocumentoIdentidadController@crear')->middleware('necesitaPermisos:documentos-identidad-crear');

            Route::get('editar/{id}', 'DocumentoIdentidadController@editar')->middleware('necesitaPermisos:documentos-identidad-editar');

            Route::post('actualizar', 'DocumentoIdentidadController@actualizar')->middleware('necesitaPermisos:documentos-identidad-editar');

            Route::get('eliminar/{id}', 'DocumentoIdentidadController@eliminar')->middleware('necesitaPermisos:documentos-identidad-eliminar');

            Route::get('obtenerDocumentos', 'DocumentoIdentidadController@obtenerDocumentos');

            Route::get('activar/{id}', 'DocumentoIdentidadController@activar')->middleware('necesitaPermisos:documentos-identidad-restaurar');

        });

        Route::group(['prefix' => 'reportes'], function () {

            Route::get('controlInterno', function(){
                return view('admin.reportes.pqr.controlInterno');
            });

        });

    });
    /*
     * Archivo
     */
    Route::group([
        'prefix' => 'archivo',
        'middleware' => 'necesitaRoles:Administrador|Coordinador Archivo|Auxiliar Archivo',
    ], function () {

        Route::get('administrar', 'ArchivoController@administrar');

        Route::get('obtenerCiudadesDpto/{idDpto}', 'ArchivoController@obternerCiudadesDpto');

        Route::get('obtenerHistorialCarpeta/{idCarpeta}', 'ArchivoController@obtenerHistorialCarpeta')->middleware('necesitaPermisos:carpeta-ver-historial');

        Route::get('obtenerTrasladoCarpeta/{idCarpeta}', 'ArchivoController@obtenerTrasladoCarpeta')->middleware('necesitaPermisos:carpeta-ver-traslado');

        Route::post('importarRegistros', 'ArchivoController@realizarImportacionRegistros')->middleware('necesitaPermisos:carpeta-importar');

        Route::get('obtenerCancelacionCarpeta/{id}', 'ArchivoController@obtenerCancelacionCarpeta');

        Route::get('obtenerMosCa', 'ArchivoController@obtenerMotivosCancelacion');

        Route::get('crearMotivoCancelacion', 'ArchivoController@nuevoMotivoCancelacion')->middleware('necesitaPermisos:carpeta-crear-motivo-cancelacion');

        Route::post('crearMotivoCancelacion', 'ArchivoController@crearMotivoCancelacion')->middleware('necesitaPermisos:carpeta-crear-motivo-cancelacion');

        Route::get('editarMotivoCancelacion/{id}', 'ArchivoController@editarMotivoCancelacion')->middleware('necesitaPermisos:carpeta-editar-motivo-cancelacion');

        Route::post('editarMotivoCancelacion', 'ArchivoController@actualizarMotivoCancelacion')->middleware('necesitaPermisos:carpeta-editar-motivo-cancelacion');

        Route::get('obtenerEstadosCarpeta', 'ArchivoController@obtenerEstadosCarpeta')->middleware('necesitaPermisos:carpeta-ver-estados');

        Route::get('crearEstadoCarpeta', 'ArchivoController@nuevoEstadoCarpeta');

        Route::post('crearEstadoCarpeta', 'ArchivoController@crearEstadoCarpeta');

        Route::get('editarEstadoCarpeta/{id}', 'ArchivoController@editarEstadoCarpeta')->middleware('necesitaPermisos:carpeta-editar-estado');

        Route::post('editarEstadoCarpeta', 'ArchivoController@actualizarEstadoCarpeta')->middleware('necesitaPermisos:carpeta-editar-estado');

        Route::post('revertirTrasladoCarpeta', 'ArchivoController@revertirTrasladoCarpeta')->middleware('necesitaPermisos:carpeta-revertir-traslado');

        Route::post('series/{txtCarpeta}/{criterioBusqueda}', 'ArchivoController@obternerCarpetas');

        Route::post('trasladarCarpeta', 'ArchivoController@realizarTrasladoCarpeta')->middleware('necesitaPermisos:carpeta-trasladar');

        Route::post('cambiarEstadoCarpeta', 'ArchivoController@cambiarEstadoCarpeta')->middleware('necesitaPermisos:carpeta-cambiar-estado');

        Route::get('exportarHistorialCarpetas/{id}', 'ArchivoController@exportarHistorialCarpeta')->middleware('necesitaPermisos:carpeta-exportar-historial');

        Route::get('editarCarpeta/{id}', 'ArchivoController@editarCarpeta')->middleware('necesitaPermisos:carpeta-editar');

        Route::post('editarCarpeta', 'ArchivoController@actualizarCarpeta')->middleware('necesitaPermisos:carpeta-editar');

        Route::post('crearCarpeta', 'ArchivoController@crearCarpeta')->middleware('necesitaPermisos:carpeta-crear');

        Route::post('cancelarCarpeta', 'ArchivoController@realizarCancelacionCarpeta')->middleware('necesitaPermisos:carpeta-cancelar');

        Route::post('revertirCancelacionCarpeta', 'ArchivoController@revertirCancelacionCarpeta')->middleware('necesitaPermisos:carpeta-revertir-cancelacion');

        Route::post('multipleEliminacion', 'ArchivoController@multipleEliminacionCarpeta')->middleware('necesitaPermisos:carpeta-eliminar');

        Route::get('multipleCambioEstado', 'ArchivoController@multipleCambioEstadoCarpetaF1')->middleware('necesitaPermisos:carpeta-cambiar-estado');

        Route::post('multipleCambioEstado', 'ArchivoController@multipleCambioEstadoCarpetaF2')->middleware('necesitaPermisos:carpeta-cambiar-estado');

        Route::get('multipleCambioClase', 'ArchivoController@multipleCambioClaseCarpetaF1')->middleware('necesitaPermisos:carpeta-cambiar-clase');

        Route::post('multipleCambioClase', 'ArchivoController@multipleCambioClaseCarpetaF2')->middleware('necesitaPermisos:carpeta-cambiar-clase');

        Route::get('cancelarCarpeta/{id}', 'ArchivoController@cancelarCarpeta')->middleware('necesitaPermisos:carpeta-cancelar');

        Route::get('trasladarCarpeta/{id}', 'ArchivoController@trasladarCarpeta')->middleware('necesitaPermisos:carpeta-trasladar');

        Route::get('revertirTrasladoCarpeta/{id}', 'ArchivoController@revertirTrasladoDeCarpeta')->middleware('necesitaPermisos:carpeta-revertir-traslado');

        Route::get('revertirCancelacionCarpeta/{id}', 'ArchivoController@revertirCancelacionDeCarpeta')->middleware('necesitaPermisos:carpeta-revertir-cancelacion');

        Route::get('cambiarEstadoCarpeta/{id}', 'ArchivoController@cambiarEstadoDeCarpeta')->middleware('necesitaPermisos:carpeta-cambiar-estado');

        Route::get('importarRegistros', 'ArchivoController@importarRegistros')->middleware('necesitaPermisos:carpeta-importar');

        Route::get('crearMultiplesCarpetas', 'ArchivoController@ingresarMultiplesCarpetas')->middleware('necesitaPermisos:carpeta-crear');

        Route::post('crearMultiplesCarpetas', 'ArchivoController@crearMultiplesCarpetas')->middleware('necesitaPermisos:carpeta-crear');

        Route::get('crearCarpeta', 'ArchivoController@nuevaCarpeta')->middleware('necesitaPermisos:carpeta-crear');

        Route::get('verSolicitudPendiente/{id}', 'ArchivoController@verSolicitudPendiente')->middleware('necesitaPermisos:carpeta-ver-solicitud');

    });
    /*
     * Solicitudes
     */
    Route::group(['prefix' => 'solicitudes'], function () {

        Route::group([
            'prefix' => 'administracion',
            'middleware' => 'necesitaRoles:Administrador|Coordinador Trámites|Auxiliar Trámites',
        ], function () {

            Route::get('procesarSolicitudes', 'SolicitudController@procesarSolicitudes');

            Route::get('entregarCarpetas', 'SolicitudController@entregarCarpetas')->middleware('necesitaPermisos:solicitud-carpeta-entregar');

            Route::get('validarSolicitudes', 'SolicitudController@validarSolicitudes')->middleware('necesitaPermisos:solicitud-carpeta-validar');

            Route::get('sinAprobar/{page?}', 'SolicitudController@solicitudesSinAprobar')->middleware('necesitaPermisos:solicitud-carpeta-aprobar');

            Route::get('sinEntregar/{page?}', 'SolicitudController@solicitudesSinEntregar')->middleware('necesitaPermisos:solicitud-carpeta-entregar');

            Route::get('sinValidar/{page?}', 'SolicitudController@solicitudesSinValidar')->middleware('necesitaPermisos:solicitud-carpeta-validar');

            Route::get('sinDevolver/{page?}', 'SolicitudController@solicitudesSinDevolver')->middleware('necesitaPermisos:solicitud-carpeta-ingresar');

            Route::get('aprobarSolicitud/{id}', 'SolicitudController@aprobarSolicitud')->middleware('necesitaPermisos:solicitud-carpeta-aprobar');

            Route::get('denegarSolicitud/{id}', 'SolicitudController@get_denegarSolicitud')->middleware('necesitaPermisos:solicitud-carpeta-denegar');

            Route::post('denegarSolicitud', 'SolicitudController@denegarSolicitud')->middleware('necesitaPermisos:solicitud-carpeta-denegar');

            Route::get('entregarCarpeta/{id}', 'SolicitudController@get_entregarCarpeta')->middleware('necesitaPermisos:solicitud-carpeta-entregar');

            Route::post('entregarCarpeta', 'SolicitudController@entregarCarpeta')->middleware('necesitaPermisos:solicitud-carpeta-entregar');

            Route::get('ingresarCarpeta/{id}', 'SolicitudController@ingresarCarpeta')->middleware('necesitaPermisos:solicitud-carpeta-ingresar');

            Route::get('validarSolicitud/{id}', 'SolicitudController@get_validarSolicitud')->middleware('necesitaPermisos:solicitud-carpeta-validar');

            Route::post('validarSolicitud', 'SolicitudController@validarSolicitud')->middleware('necesitaPermisos:solicitud-carpeta-validar');

            Route::get('obtenerTiposValidaciones/{page?}','SolicitudController@obtenerTiposValidaciones');

            Route::get('crearTipoValidacion','SolicitudController@nuevoTipoValidacion')->middleware('necesitaPermisos:solicitud-carpeta-crear-tipo-validacion');

            Route::post('crearTipoValidacion','SolicitudController@crearTipoValidacion')->middleware('necesitaPermisos:solicitud-carpeta-crear-tipo-validacion');

            Route::get('editarTipoValidacion/{id}','SolicitudController@editarTipoValidacion')->middleware('necesitaPermisos:solicitud-carpeta-editar-tipo-validacion');

            Route::post('editarTipoValidacion','SolicitudController@actualizarTipoValidacion')->middleware('necesitaPermisos:solicitud-carpeta-editar-tipo-validacion');

            Route::get('obtenerMotivosSolicitud/{page?}','SolicitudController@obtenerMotivosSolicitud');

            Route::get('nuevoMotivoSolicitud','SolicitudController@nuevoMotivoSolicitud')->middleware('necesitaPermisos:solicitud-carpeta-crear-motivo-solicitud');

            Route::post('crearMotivoSolicitud','SolicitudController@crearMotivoSolicitud')->middleware('necesitaPermisos:solicitud-carpeta-crear-motivo-solicitud');

            Route::get('editarMotivoSolicitud/{id}','SolicitudController@editarMotivoSolicitud')->middleware('necesitaPermisos:solicitud-carpeta-editar-motivo-solicitud');

            Route::post('editarMotivoSolicitud','SolicitudController@actualizarMotivoSolicitud')->middleware('necesitaPermisos:solicitud-carpeta-editar-motivo-solicitud');

            Route::get('obtenerMotivosDenegacion/{page?}','SolicitudController@obtenerMotivosDenegacion');

            Route::get('nuevoMotivoDenegacion','SolicitudController@nuevoMotivoDenegacion')->middleware('necesitaPermisos:solicitud-carpeta-crear-motivo-denegacion');

            Route::post('crearMotivoDenegacion','SolicitudController@crearMotivoDenegacion')->middleware('necesitaPermisos:solicitud-carpeta-crear-motivo-denegacion');

            Route::get('editarMotivoDenegacion/{id}','SolicitudController@editarMotivoDenegacion')->middleware('necesitaPermisos:solicitud-carpeta-editar-motivo-denegacion');

            Route::post('editarMotivoDenegacion','SolicitudController@actualizarMotivoDenegacion')->middleware('necesitaPermisos:solicitud-carpeta-editar-motivo-denegacion');

            Route::group(['prefix' => 'filtro'], function () {

                Route::get('sinAprobar/{parametro}/{criterio}', 'SolicitudController@filtrarSinAprobar');

                Route::get('sinEntregar/{parametro}/{criterio}', 'SolicitudController@filtrarSinEntregar');

                Route::get('sinDevolver/{parametro}/{criterio}', 'SolicitudController@filtrarSinDevolver');

                Route::get('sinValidar/{parametro}/{criterio}', 'SolicitudController@filtrarSinValidar');
            });

        });

        Route::get('misSolicitudes', 'SolicitudController@misSolicitudes_index');

        Route::group(['prefix' => 'misSolicitudes'], function () {

            Route::get('obtenerMisSolicitudes/{page?}', 'SolicitudController@misSolicitudes_todas');

            Route::get('crear', 'SolicitudController@misSolicitudes_crear');

            Route::post('registrar', 'SolicitudController@misSolicitudes_registrar');

        });

        Route::get('eliminarSolicitud/{id}', 'SolicitudController@eliminarSolicitud');

        Route::get('actualizarSolicitud/{id}', 'SolicitudController@obtenerSolicitud');

        Route::post('actualizarSolicitud', 'SolicitudController@actualizarSolicitud');

        Route::get('filtrarBusqueda/{parametro}/{page?}', 'SolicitudController@filtrarBusqueda');

    });
    /*
     * Tramites - Placas
     */
    Route::group(['prefix' => 'tramites', 'middleware' => 'necesitaRoles:Administrador|Coordinador Trámites|Auxiliar Trámites'], function () {

        Route::group(['prefix' => 'to'], function () {

            Route::get('administrar', 'TOController@administrar');

            Route::get('crear', 'TOController@nuevaTO')->middleware('necesitaPermisos:tarjeta-operacion-crear');

            Route::post('crear', 'TOController@crear')->middleware('necesitaPermisos:tarjeta-operacion-crear');

            Route::get('editar/{id}', 'TOController@editar')->middleware('necesitaPermisos:tarjeta-operacion-editar');

            Route::post('editar', 'TOController@guardarCambios')->middleware('necesitaPermisos:tarjeta-operacion-editar');

            Route::get('imprimir/{param}', 'TOController@imprimir')->middleware('necesitaPermisos:tarjeta-operacion-imprimir');

            Route::get('obtenerTSO/{page?}', 'TOController@obtenerTSO');

            Route::get('ver/{id}', 'TOController@ver');

            Route::get('verificarVigencia/{placa}', 'TOController@verificarVigencia');

            Route::get('filtrarBusqueda/{parametro}/{page?}', 'TOController@filtrarBusqueda');

            Route::get('obtenerDatosVehiculo/{placa}', 'TOController@obtenerDatosVehiculo');

        });
        
        Route::group(['prefix' => 'vehiculos', 'middleware' => 'necesitaPermisos:vehiculo-administrar'], function () {

            Route::group(['prefix' => 'filtrar'], function () {

                Route::get('vehiculos/{parametro}/{filtro}/{page?}', 'VehiculoController@filtrarVehiculos');

            });

            Route::get('administrar', 'VehiculoController@administrar')->middleware('necesitaPermisos:vehiculo-administrar');

            Route::get('eliminarMarca/{id}', 'VehiculoController@eliminarMarca')->middleware('necesitaPermisos:vehiculo-eliminar-marca');

            Route::get('restaurarMarca/{id}', 'VehiculoController@restaurarMarca')->middleware('necesitaPermisos:vehiculo-restaurar-marca');

            Route::get('eliminarClase/{id}', 'VehiculoController@eliminarClase')->middleware('necesitaPermisos:vehiculo-eliminar-clase');

            Route::get('restaurarClase/{id}', 'VehiculoController@restaurarClase')->middleware('necesitaPermisos:vehiculo-restaurar-clase');

            Route::get('eliminarCarroceria/{id}', 'VehiculoController@eliminarCarroceria')->middleware('necesitaPermisos:vehiculo-eliminar-carroceria');

            Route::get('restaurarCarroceria/{id}', 'VehiculoController@restaurarCarroceria')->middleware('necesitaPermisos:vehiculo-restaurar-carroceria');

            Route::get('eliminarCombustible/{id}', 'VehiculoController@eliminarCombustible')->middleware('necesitaPermisos:vehiculo-eliminar-combustible');

            Route::get('restaurarCombustible/{id}', 'VehiculoController@restaurarCombustible')->middleware('necesitaPermisos:vehiculo-restaurar-combustible');

            Route::post('nuevaClase', 'VehiculoController@nuevaClase')->middleware('necesitaPermisos:vehiculo-crear-clase');

            Route::post('nuevaMarca', 'VehiculoController@nuevaMarca')->middleware('necesitaPermisos:vehiculo-crear-marca');

            Route::post('nuevoCombustible', 'VehiculoController@nuevoCombustible')->middleware('necesitaPermisos:vehiculo-crear-combustible');

            Route::post('nuevaCarroceria', 'VehiculoController@nuevaCarroceria')->middleware('necesitaPermisos:vehiculo-crear-carroceria');

            Route::get('obtenerCarrocerias/{page?}', 'VehiculoController@obtenerCarrocerias');

            Route::get('obtenerClases/{page?}', 'VehiculoController@obtenerClases');

            Route::get('obtenerCombustibles/{page?}', 'VehiculoController@obtenerCombustibles');

            Route::get('obtenerMarcas/{page?}', 'VehiculoController@obtenerMarcas');

            Route::get('editarClase/{id}', 'VehiculoController@editarClase')->middleware('necesitaPermisos:vehiculo-editar-clase');

            Route::post('editarClase', 'VehiculoController@actualizarClase')->middleware('necesitaPermisos:vehiculo-editar-clase');

            Route::get('editarMarca/{id}', 'VehiculoController@editarMarca')->middleware('necesitaPermisos:vehiculo-editar-marca');

            Route::post('editarMarca', 'VehiculoController@actualizarMarca')->middleware('necesitaPermisos:vehiculo-editar-marca');

            Route::get('editarCarroceria/{id}', 'VehiculoController@editarCarroceria')->middleware('necesitaPermisos:vehiculo-editar-carroceria');

            Route::post('editarCarroceria', 'VehiculoController@actualizarCarroceria')->middleware('necesitaPermisos:vehiculo-editar-carroceria');

            Route::get('editarCombustible/{id}', 'VehiculoController@editarCombustible')->middleware('necesitaPermisos:vehiculo-editar-combustible');

            Route::post('editarCombustible', 'VehiculoController@actualizarCombustible')->middleware('necesitaPermisos:vehiculo-editar-combustible');

            Route::get('obtenerLetrasClaseVehiculo/{id}', 'VehiculoController@obtenerLetrasClaseVehiculo');

            Route::get('nuevaMarca', 'VehiculoController@crearMarca')->middleware('necesitaPermisos:vehiculo-crear-marca');

            Route::get('nuevaClase', 'VehiculoController@crearClase')->middleware('necesitaPermisos:vehiculo-crear-clase');

            Route::get('nuevaCarroceria', 'VehiculoController@crearCarroceria')->middleware('necesitaPermisos:vehiculo-crear-carroceria');

            Route::get('nuevoCombustible', 'VehiculoController@crearCombustible')->middleware('necesitaPermisos:vehiculo-crear-combustible');

            Route::get('obtenerVehiculos/{page?}', 'VehiculoController@obtenerVehiculos');

            Route::get('nuevoVehiculo', 'VehiculoController@nuevoVehiculo')->middleware('necesitaPermisos:vehiculo-crear');

            Route::post('nuevoVehiculo', 'VehiculoController@crearVehiculo')->middleware('necesitaPermisos:vehiculo-crear');

            Route::get('editarVehiculo/{id}', 'VehiculoController@obtenerVehiculo')->middleware('necesitaPermisos:vehiculo-editar');

            Route::post('editarVehiculo', 'VehiculoController@guardarCambiosVehiculo')->middleware('necesitaPermisos:vehiculo-editar');

            Route::get('vincularEmpresa/{id}', 'VehiculoController@vincularEmpresa')->middleware('necesitaPermisos:vehiculo-vincular-empresa');

            Route::post('vincularEmpresa', 'VehiculoController@vincularUnaEmpresa')->middleware('necesitaPermisos:vehiculo-vincular-empresa');

            Route::get('verVinculacion/{id}', 'VehiculoController@verVinculacion')->middleware('necesitaPermisos:vehiculo-ver-empresa');

            Route::post('cambiosVinculacionEmpresa', 'VehiculoController@cambiosVinculacionEmpresa')->middleware('necesitaPermisos:vehiculo-editar-empresa');
            
            Route::get('eliminarServicio/{id}', 'VehiculoController@eliminarServicio')->middleware('necesitaPermisos:vehiculo-eliminar-servicio');

            Route::get('restaurarServicio/{id}', 'VehiculoController@restaurarServicio')->middleware('necesitaPermisos:vehiculo-restaurar-servicio');

            Route::get('nuevoServicio', 'VehiculoController@crearServicio')->middleware('necesitaPermisos:vehiculo-crear-servicio');

            Route::post('nuevoServicio', 'VehiculoController@nuevoServicio')->middleware('necesitaPermisos:vehiculo-crear-servicio');

            Route::get('editarServicio/{id}', 'VehiculoController@editarServicio')->middleware('necesitaPermisos:vehiculo-editar-servicio');

            Route::post('editarServicio', 'VehiculoController@actualizarServicio')->middleware('necesitaPermisos:vehiculo-editar-servicio');

            Route::get('obtenerServicios/{page?}', 'VehiculoController@obtenerServicios');

            Route::get('obtenerLineasJSON/{id}', 'VehiculoController@obtenerLineasJSON');

            Route::get('nuevaLinea', 'VehiculoController@nuevaLinea')->middleware('necesitaPermisos:vehiculo-crear-linea');

            Route::post('crearLinea', 'VehiculoController@crearLinea')->middleware('necesitaPermisos:vehiculo-crear-linea');

            Route::get('editarLinea/{id}', 'VehiculoController@editarLinea')->middleware('necesitaPermisos:vehiculo-editar-linea');

            Route::post('actualizarLinea', 'VehiculoController@actualizarLinea')->middleware('necesitaPermisos:vehiculo-editar-linea');

            Route::get('obtenerLineas', 'VehiculoController@obtenerLineas');

            Route::get('verPropietarios/{id}','VehiculoController@obtenerPropietarios');

            Route::get('nuevoPropietario/{id}', 'VehiculoController@nuevoPropietrario')->middleware('necesitaPermisos:vehiculo-crear-propietario');

            Route::post('nuevoPropietario', 'VehiculoController@crearPropietario')->middleware('necesitaPermisos:vehiculo-crear-propietario');

            Route::get('editarPropietario/{id}', 'VehiculoController@editarPropietrario')->middleware('necesitaPermisos:vehiculo-editar-propietario');

            Route::post('editarPropietario', 'VehiculoController@actualizarPropietario')->middleware('necesitaPermisos:vehiculo-editar-propietario');

            Route::get('retirarPropietario/{propietarioId}/{vehiculoId}','VehiculoController@retirarPropietario')->middleware('necesitaPermisos:vehiculo-retirar-propietario');

            Route::get('vincularPropietario/{propietarioId}/{vehiculoId}','VehiculoController@vincularPropietario')->middleware('necesitaPermisos:vehiculo-retirar-propietario');

            Route::get('obtenerTiposBaterias', 'VehiculoController@obtenerTiposBaterias');

            Route::get('nuevoTipoBateria','VehiculoController@nuevoTipoBateria')->middleware('necesitaPermisos:vehiculo-crear-tipo-bateria');

            Route::post('crearTipoBateria','VehiculoController@crearTipoBateria')->middleware( 'necesitaPermisos:vehiculo-crear-tipo-bateria');

            Route::get('editarTipoBateria/{id}','VehiculoController@editarTipoBateria')->middleware( 'necesitaPermisos:vehiculo-editar-tipo-bateria');

            Route::post('actualizarTipoBateria','VehiculoController@actualizarTipoBateria')->middleware( 'necesitaPermisos:vehiculo-editar-tipo-bateria');

        });
        
        Route::group(['prefix' => 'placas', 'middleware' => 'necesitaRoles:Administrador|Coordinador Trámites|Funcionario EV'], function () {

            Route::get('administrar', 'PlacaController@administrar')->middleware('necesitaPermisos:placa-administrar');

            Route::get('nuevasPlacas', 'PlacaController@nuevasPlacas')->middleware('necesitaPermisos:placa-crear');

            Route::get('obtenerPlacas', 'PlacaController@obtenerPlacas');

            Route::post('ingresarPlacas', 'PlacaController@ingresarPlacas')->middleware('necesitaPermisos:placa-crear');

            Route::get('serviciosPorClase/{clase_id}', 'PlacaController@getServiciosPorClaseVehiculo');

            Route::get('letrasTerminacionPorClase/{clase_id}', 'PlacaController@getLetrasTerminacionPorClaseVehiculo');

            Route::get('editarPlaca/{id}', 'PlacaController@editarPlaca')->middleware('necesitaPermisos:placa-editar');

            Route::post('editarPlaca', 'PlacaController@actualizarPlaca')->middleware('necesitaPermisos:placa-editar');

            Route::post('multipleLiberacionPlacas', 'PlacaController@multipleLiberacionPlacas')->middleware('necesitaPermisos:placa-liberar');

            Route::get('liberarPlaca/{id}', 'PlacaController@liberarPlaca')->middleware('necesitaPermisos:placa-liberar');

            Route::post('solicitarReportePlacasPedidas', 'PlacaController@generarReportePlacasPedidas');

        });

        Route::group(['prefix' => 'preAsignaciones', 'middleware' => 'necesitaRoles:Administrador|Coordinador Trámites|Auxiliar Trámites'], function () {

            Route::get('administrar', 'PreAsignacionesController@administrar')->middleware('necesitaPermisos:preasignacion-administrar');

            Route::get('placasDisponibles/{solicitud_id}', 'PreAsignacionesController@obtenerPlacasParaPreAsignar');

            Route::post('preAsignarPlaca', 'PreAsignacionesController@preAsignarPlaca')->middleware('necesitaPermisos:preasignacion-crear');

            Route::get('obtenerMotivosRechazo', 'PreAsignacionesController@obtenerMotivosRechazo');

            Route::post('rechazarSolicitud', 'PreAsignacionesController@rechazarSolicitud')->middleware('necesitaPermisos:preasignacion-rechazar');

            Route::get('obtenerSolicitudes', 'PreAsignacionesController@obtenerSolicitudes');

            Route::get('liberarSolicitud/{id}', 'PreAsignacionesController@liberarSolicitud')->middleware('necesitaPermisos:preasignacion-liberar');

            Route::get('rechazarLaSolicitud/{id}', 'PreAsignacionesController@rechazarLaSolicitud')->middleware('necesitaPermisos:preasignacion-rechazar');

            Route::get('verManifiesto/{id}', 'PreAsignacionesController@obtenerManifiesto');

            Route::get('verFactura/{id}', 'PreAsignacionesController@obtenerFactura');

            Route::get('verCedulaPropietario/{id}', 'PreAsignacionesController@obtenerCedulaPropietario');

            Route::get('nuevaPreasignacion', 'PreAsignacionesController@nuevaPreasignacion')->middleware('necesitaPermisos:preasignacion-crear');

            Route::post('nuevaPreasignacion', 'PreAsignacionesController@crearPreasignacion')->middleware('necesitaPermisos:preasignacion-crear');

            Route::get('obtenerMotivosRechazo/{page?}', 'PreAsignacionesController@obtenerMotivosRechazo');

            Route::get('editarMotivoRechazo/{id}', 'PreAsignacionesController@editarMotivoRechazo')->middleware('necesitaPermisos:preasignacion-editar-motivo-rechazo');

            Route::post('editarMotivoRechazo', 'PreAsignacionesController@actualizarMotivoRechazo')->middleware('necesitaPermisos:preasignacion-editar-motivo-rechazo');

            Route::get('eliminarMotivoRechazo/{id}','PreAsignacionesController@eliminarMotivoRechazo')->middleware('necesitaPermisos:preasignacion-eliminar-motivo-rechazo');

            Route::get('restaurarMotivoRechazo/{id}','PreAsignacionesController@restaurarMotivoRechazo')->middleware('necesitaPermisos:preasignacion-eliminar-motivo-rechazo');

            Route::get('crearMotivoRechazo', 'PreAsignacionesController@nuevoMotivoRechazo')->middleware('necesitaPermisos:preasignacion-crear-motivo-rechazo');

            Route::post('crearMotivoRechazo', 'PreAsignacionesController@crearMotivoRechazo')->middleware('necesitaPermisos:preasignacion-crear-motivo-rechazo');

            Route::get('matricularSolicitud/{id}','PreAsignacionesController@matricularPreasignacion')->middleware('necesitaPermisos:preasignacion-matricular');

            Route::get('subirManifiesto/{id}', 'PreAsignacionesController@subirManifiesto')->middleware('necesitaPermisos:preasignacion-matricular');

            Route::post('subirManifiesto', 'PreAsignacionesController@guardarManifiesto');

            Route::get('subirFactura/{id}', 'PreAsignacionesController@subirFactura');

            Route::post('subirFactura', 'PreAsignacionesController@guardarFactura');

        });

        Route::group(['prefix' => 'solicitudes', 'middleware' => 'necesitaRoles:Administrador|Coordinador Trámites|Auxiliar Trámites'], function () {

            Route::get('administrar', 'TramitesSolicitudesController@administrar')->middleware('necesitaPermisos:solicitud-tramite-administrar');

            Route::get('nuevaSolicitud', 'TramitesSolicitudesController@nuevaSolicitud')->middleware('necesitaPermisos:solicitud-tramite-crear');

            Route::get('obtenerTodas', 'TramitesSolicitudesController@obtenerSolicitudes')->middleware('necesitaPermisos:solicitud-tramite-administrar');

            Route::get('asignarEstadoSolicitud/{solicitud_id}/{estado_id}', 'TramitesSolicitudesController@asignarEstadoASolicitud')->middleware('necesitaPermisos:solicitud-tramite-asignar-estado');

            Route::get('obtenerSolicitud/{id}', 'TramitesSolicitudesController@obtenerSolicitud');

            Route::get('obtenerDocumentacion/{id}', 'TramitesSolicitudesController@obtenerDocumentacion');

            Route::get('obtenerAsignaciones/{id}', 'TramitesSolicitudesController@obtenerAsignaciones');

            Route::post('registrarSolicitud', 'TramitesSolicitudesController@crearSolicitud')->middleware('necesitaPermisos:solicitud-tramite-crear');

            Route::get('llamarTurno', 'TurnoController@llamarTurno')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::get('ventanilla', 'TramitesSolicitudesController@moduloVentanilla')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::get('obtenerCarpetasServicio/{id}', 'TramitesSolicitudesController@obtenerCarpetasServicio')->middleware('necesitaPermisos:solicitud-tramite-actualizar-estado');

            Route::get('obtenerEstadosServicio/{id}', 'TramitesSolicitudesController@obtenerEstadosServicio')->middleware('necesitaPermisos:solicitud-tramite-actualizar-estado');

            Route::get('asignarEstadoServicio/{servicio_id}', 'TramitesSolicitudesController@asignarEstadoServicio')->middleware('necesitaPermisos:solicitud-tramite-asignar-estado');

            Route::post('asignarEstadoServicio', 'TramitesSolicitudesController@asignarEstado')->middleware('necesitaPermisos:solicitud-tramite-asignar-estado');

            Route::get('obtenerMisTramites/{page?}', 'TramitesSolicitudesController@obtenerMisTramites');

            Route::post('finalizarTurnoF2', 'TramitesSolicitudesController@finalizarTurnoF2')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::get('verTurnos/{solicitud_id}', 'TramitesSolicitudesController@verTurnos');

            Route::get('verRadicados/{solicitud_id}', 'TramitesSolicitudesController@verRadicados');

            Route::get('verServicios/{solicitud_id}', 'TramitesSolicitudesController@verServicios');

            Route::get('verEstadosServicio/{servicio_id}', 'TramitesSolicitudesController@verEstadosServicio');

            Route::get('verCarpetasServicio/{servicio_id}', 'TramitesSolicitudesController@verCarpetasServicio');

            Route::get('verRecibosServicio/{servicio_id}', 'TramitesSolicitudesController@verRecibosServicio');

            Route::get('verFinalizacionServicio/{servicio_id}', 'TramitesSolicitudesController@verFinalizacionServicio');

            Route::get('verAsignaciones/{solicitud_id}', 'TramitesSolicitudesController@verAsignaciones');

            Route::get('obtenerTramites/{page?}', 'TramitesSolicitudesController@obtenerTramites');

            Route::get('establecerVentanilla', 'TramitesSolicitudesController@obtenerVentanillasAsignacion')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::post('establecerVentanilla', 'TramitesSolicitudesController@asignarFuncionarioEnVentanilla')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::get('nuevoTurno/{tramite_id}', 'TurnoController@nuevoTurno')->middleware('necesitaPermisos:solicitud-tramite-crear');

            Route::post('nuevoTurno', 'TurnoController@crearNuevoTurno')->middleware('necesitaPermisos:solicitud-tramite-crear');

            Route::get('editarSolicitud/{tramite_id}', 'TramitesSolicitudesController@editarSolicitud')->middleware('necesitaPermisos:solicitud-tramite-editar');

            Route::post('editarSolicitud', 'TramitesSolicitudesController@actualizarSolicitud')->middleware('necesitaPermisos:solicitud-tramite-editar');

            Route::get('finalizarTramiteF1/{solicitud_id}', 'TramitesSolicitudesController@finalizarTramiteF1')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::post('finalizarTramiteF2', 'TramitesSolicitudesController@finalizarTramiteF2')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::get('obtenerFinalizacionServicio/{solicitud_id}', 'TramitesSolicitudesController@obtenerFinalizacionServicio');

            Route::get('finalizarTurnoF1/{turno_id}/{solicitud_id}/{ventanilla_id}', 'TramitesSolicitudesController@finalizarTurnoF1')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::post('finalizarTurnoF2', 'TramitesSolicitudesController@finalizarTurnoF2')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::get('verTurno/{id}', 'TurnoController@verTurno');

            Route::get('obtenerListadoOrigenes/{page?}', 'TramitesSolicitudesController@obtenerListadoOrigenes');

            Route::get('nuevoOrigen', 'TramitesSolicitudesController@nuevoOrigen')->middleware('necesitaPermisos:solicitud-tramite-crear-origen');

            Route::post('nuevoOrigen', 'TramitesSolicitudesController@crearOrigen')->middleware('necesitaPermisos:solicitud-tramite-crear-origen');

            Route::get('editarOrigen/{id}', 'TramitesSolicitudesController@editarOrigen')->middleware('necesitaPermisos:solicitud-tramite-editar-origen');

            Route::post('editarOrigen', 'TramitesSolicitudesController@actualizarOrigen')->middleware('necesitaPermisos:solicitud-tramite-editar-origen');

            Route::get('solicitarCarpeta/{id}', 'TramitesSolicitudesController@solicitarCarpeta')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::get('obtenerListadoEstados/{page?}', 'TramitesSolicitudesController@obtenerListadoEstados');

            Route::get('nuevoEstado', 'TramitesSolicitudesController@nuevoEstado')->middleware('necesitaPermisos:solicitud-tramite-crear-estado');

            Route::post('nuevoEstado', 'TramitesSolicitudesController@crearEstado')->middleware('necesitaPermisos:solicitud-tramite-crear-estado');

            Route::get('editarEstado/{id}', 'TramitesSolicitudesController@editarEstado')->middleware('necesitaPermisos:solicitud-tramite-editar-estado');

            Route::post('editarEstado', 'TramitesSolicitudesController@actualizarEstado')->middleware('necesitaPermisos:solicitud-tramite-editar-estado');

            Route::get('subirRecibos/{id}','TramitesSolicitudesController@subirRecibos')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::post('subirRecibos', 'TramitesSolicitudesController@vincularRecibos')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::get('obtenerRecibosServicio/{id}', 'TramitesSolicitudesController@obtenerRecibosServicio');

            Route::get('verCUPL/{id}', 'TramitesSolicitudesController@verCUPL');

            Route::get('verSINTRAT/{id}', 'TramitesSolicitudesController@verSINTRAT');

            Route::get('verCONSIGNACION/{id}', 'TramitesSolicitudesController@verCONSIGNACION');

            Route::get('reImprimirTurno/{id}', 'TurnoController@reImprimirTurno')->middleware('necesitaPermisos:solicitud-tramite-administrar');

            Route::get('obtenerServiciosSolicitud/{solicitud_id}', 'TramitesSolicitudesController@obtenerServiciosSolicitud');

            Route::get('agregarServicioSolicitud/{solicitud_id}', 'TramitesSolicitudesController@agregarServicioSolicitud')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::post('agregarServicioSolicitud', 'TramitesSolicitudesController@vincularServicioSolicitud')->middleware('necesitaPermisos:solicitud-tramite-llamar-turno');

            Route::get('anularSustrato/{finalizacionId}/{sustratoId}', 'TramitesSolicitudesController@anularSustratoF1');

            Route::post('anularSustrato', 'TramitesSolicitudesController@anularSustratoF2');

            Route::get('reLlamarTurno/{ventanillaId}',  'TramitesSolicitudesController@reLlamarTurnoF1');

            Route::post('reLlamarTurno',  'TramitesSolicitudesController@reLlamarTurnoF2');

            Route::get('filtrar/{parametros}/{valor}/{page?}', 'TramitesSolicitudesController@filtrarSolicitudes');

            Route::get('filtrarMisTramites/{parametros}/{valor}/{page?}', 'TramitesSolicitudesController@filtrarSolicitudes');

            Route::get('obtenerListadoLicencias/{id}', 'TramitesSolicitudesController@obtenerLicenciasSolicitud');
           
            Route::get('nuevaLicencia/{id}', 'TramitesSolicitudesController@nuevaLicenciaSolicitud');

            Route::post('registrarLicencia', 'TramitesSolicitudesController@registrarLicenciaSolicitud');

            Route::get('solicitarDescanso', 'TramitesSolicitudesController@solicitarDescanso');

            Route::post('solicitarDescanso', 'TramitesSolicitudesController@registrarDescanso');

            Route::get('obtenerListadoMotivosDescanso/{page?}', 'TramitesSolicitudesController@obtenerListadoMotivosDescanso');

            Route::get('nuevoMotivoDescanso', 'TramitesSolicitudesController@nuevoMotivoDescanso')->middleware('necesitaPermisos:solicitud-tramite-crear-motivo-descanso');

            Route::post('nuevoMotivoDescanso', 'TramitesSolicitudesController@crearMotivoDescanso')->middleware('necesitaPermisos:solicitud-tramite-crear-motivo-descanso');

            Route::get('editarMotivoDescanso/{id}', 'TramitesSolicitudesController@editarMotivoDescanso')->middleware('necesitaPermisos:solicitud-editar-motivo-descanso');

            Route::post('editarMotivoDescanso', 'TramitesSolicitudesController@actualizarMotivoDescanso')->middleware('necesitaPermisos:solicitud-editar-motivo-descanso');

            Route::post('generarDevolucion', 'TramitesSolicitudesController@generarDevolucionTramite');

            Route::get('verLicencias/{id}', 'TramitesSolicitudesController@verLicencias');

            Route::get('editarSolicitante/{turnoId}', 'TramitesSolicitudesController@editarSolicitante')->middleware('necesitaPermisos:solicitud-editar-solicitante');

            Route::post('actualizarSolicitante', 'TramitesSolicitudesController@actualizarSolicitante')->middleware('necesitaPermisos:solicitud-editar-solicitante');

            Route::get('editarLicencia/{licenciaId}', 'TramitesSolicitudesController@editarLicenciaSolicitud')->middleware('necesitaPermisos:solicitud-editar-licencia');

            Route::post('actualizarLicencia', 'TramitesSolicitudesController@actualizarLicenciaSolicitud')->middleware('necesitaPermisos:solicitud-editar-licencia');

        });

        Route::group(['prefix' => 'sustratos', 'middleware' => 'necesitaRoles:Administrador|Coordinador Trámites|Funcionario EV'], function () {

            Route::get('administrar', 'SustratoController@administrar');

            Route::get('editarSustrato/{id}', 'SustratoController@editarSustrato')->middleware('necesitaPermisos:sustrato-editar');

            Route::post('editarSustrato', 'SustratoController@actualizarSustrato')->middleware('necesitaPermisos:sustrato-editar');

            Route::get('obtenerSustratos{page?}', 'SustratoController@obtenerSustratos');

            Route::post('ingresarSustratos', 'SustratoController@nuevosSustratos')->middleware('necesitaPermisos:sustrato-crear');

            Route::get('nuevosSustratos', 'SustratoController@crearNuevosSustratos')->middleware('necesitaPermisos:sustrato-crear');

            Route::get('obtenerListadoTiposSustratos/{page?}', 'SustratoController@obtenerListadoTiposSustratos');

            Route::get('nuevoTipoSustrato', 'SustratoController@nuevoTipoSustrato')->middleware('necesitaPermisos:sustrato-crear-tipo');

            Route::post('nuevoTipoSustrato', 'SustratoController@crearTipoSustrato')->middleware('necesitaPermisos:sustrato-crear-tipo');

            Route::get('editarTipoSustrato/{id}', 'SustratoController@editarTipoSustrato')->middleware('necesitaPermisos:sustrato-editar-tipo');

            Route::post('editarTipoSustrato', 'SustratoController@actualizarTipoSustrato')->middleware('necesitaPermisos:sustrato-editar-tipo');

            Route::get('obtenerListadoMotivosAnulaciones/{page?}', 'SustratoController@obtenerListadoMotivosAnulaciones');

            Route::get('nuevoMotivoAnulacion', 'SustratoController@nuevoMotivoAnulacion')->middleware('necesitaPermisos:sustrato-crear-motivo-anulacion');

            Route::post('nuevoMotivoAnulacion', 'SustratoController@crearMotivoAnulacion')->middleware('necesitaPermisos:sustrato-crear-motivo-anulacion');

            Route::get('editarMotivoAnulacion/{id}', 'SustratoController@editarMotivoAnulacion')->middleware('necesitaPermisos:sustrato-editar-motivo-anulacion');

            Route::post('editarMotivoAnulacion', 'SustratoController@actualizarMotivoAnulacion')->middleware('necesitaPermisos:sustrato-editar-motivo-anulacion');

            Route::get('verConsumo/{id}', 'SustratoController@verConsumo');

            Route::get('verAnulacion/{id}', 'SustratoController@verAnulacion');

            Route::post('filtrarSustratos', 'SustratoController@filtrarSustratos');

            Route::get('anularSustratoConsumido/{id}', 'SustratoController@anularSustratoF1')->middleware('necesitaPermisos:sustrato-anular-consumido');

            Route::post('anularSustratoConsumido', 'SustratoController@anularSustratoF2')->middleware('necesitaPermisos:sustrato-anular-consumido');

            Route::post('solicitarReporteSustratos', 'SustratoController@generarReporteSustratos');

            Route::get('obtenerListadoMotivosLiberaciones/{page?}', 'SustratoController@obtenerListadoMotivosLiberaciones');

            Route::get('nuevoMotivoLiberacion', 'SustratoController@nuevoMotivoLiberacion')->middleware('necesitaPermisos:sustrato-crear-motivo-liberacion');

            Route::post('nuevoMotivoLiberacion', 'SustratoController@crearMotivoLiberacion')->middleware('necesitaPermisos:sustrato-crear-motivo-liberacion');

            Route::get('editarMotivoLiberacion/{id}', 'SustratoController@editarMotivoLiberacion')->middleware('necesitaPermisos:sustrato-editar-motivo-liberacion');

            Route::post('editarMotivoLiberacion', 'SustratoController@actualizarMotivoLiberacion')->middleware('necesitaPermisos:sustrato-editar-motivo-liberacion');

            Route::get('verLiberaciones/{id}', 'SustratoController@verLiberaciones');

            Route::get('liberarSustratoConsumido/{id}', 'SustratoController@liberarSustratoF1')->middleware('necesitaPermisos:sustrato-liberar-consumido');

            Route::post('liberarSustratoConsumido', 'SustratoController@liberarSustratoF2')->middleware('necesitaPermisos:sustrato-liberar-consumido');

            Route::get('restaurarSustrato/{id}', 'SustratoController@restaurarSustrato')->middleware('necesitaPermisos:sustrato-restaurar-anulado');
            
        });

        Route::group(['prefix' => 'ventanillas', 'middleware' => 'necesitaPermisos:ventanilla-administrar'], function () {

            Route::get('administrar', 'VentanillaController@administrar');

            Route::get('nuevaVentanilla', 'VentanillaController@nuevaVentanilla')->middleware('necesitaPermisos:ventanilla-crear');

            Route::post('nuevaVentanilla', 'VentanillaController@crearVentanilla')->middleware('necesitaPermisos:ventanilla-crear');

            Route::get('editarVentanilla/{id}', 'VentanillaController@editarVentanilla')->middleware('necesitaPermisos:ventanilla-editar');

            Route::post('editarVentanilla', 'VentanillaController@actualizarVentanilla')->middleware('necesitaPermisos:ventanilla-editar');

            Route::get('obtenerVentanillas', 'VentanillaController@obtenerVentanillas');

        });

        Route::group(['prefix' => 'impuestos', 'middleware' => 'necesitaPermisos:liquidacion-impuesto-liquidar'], function () {
        
            Route::get('administrar', 'ImpuestoController@administar');

            Route::get('obtenerInfoVehiculo/{placa}', 'ImpuestoController@obtenerInfoVehiculo');

            Route::get('obtenerLiquidaciones/{placa}', 'ImpuestoController@obtenerLiquidaciones');

            Route::get('nuevaLiquidacion/{id}', 'ImpuestoController@nuevaLiquidacion')->middleware('necesitaPermisos:liquidacion-impuesto-crear');

            Route::get('obtenerVigencias', 'ImpuestoController@obtenerVigencias');

            Route::get('nuevaVigencia', 'ImpuestoController@nuevaVigencia')->middleware('necesitaPermisos:liquidacion-impuesto-crear-vigencia');

            Route::post('nuevaVigencia', 'ImpuestoController@crearVigencia')->middleware('necesitaPermisos:liquidacion-impuesto-crear-vigencia');

            Route::get('editarVigencia/{id}', 'ImpuestoController@editarVigencia')->middleware('necesitaPermisos:liquidacion-impuesto-editar-vigencia');

            Route::post('editarVigencia', 'ImpuestoController@actualizarVigencia')->middleware('necesitaPermisos:liquidacion-impuesto-editar-vigencia');

            Route::get('obtenerBasesGravables/{page?}', 'ImpuestoController@obtenerBasesGravables');

            Route::get('nuevaBaseGravable', 'ImpuestoController@nuevaBaseGravable')->middleware('necesitaPermisos:liquidacion-impuesto-crear-base-gravable');

            Route::post('crearBaseGravable', 'ImpuestoController@crearBaseGravable')->middleware('necesitaPermisos:liquidacion-impuesto-crear-base-gravable');

            Route::get('editarBaseGravable/{id}', 'ImpuestoController@editarBaseGravable')->middleware('necesitaPermisos:liquidacion-impuesto-editar-base-gravable');

            Route::post('editarBaseGravable', 'ImpuestoController@actualizarBaseGravable')->middleware('necesitaPermisos:liquidacion-impuesto-editar-base-gravable');

            Route::get('obtenerDescuentos/{page?}', 'ImpuestoController@obtenerDescuentos');

            Route::get('nuevoDescuento', 'ImpuestoController@nuevoDescuento')->middleware('necesitaPermisos:liquidacion-impuesto-crear-descuento');

            Route::post('crearDescuento', 'ImpuestoController@crearDescuento')->middleware('necesitaPermisos:liquidacion-impuesto-crear-descuento');

            Route::get('editarDescuento/{id}', 'ImpuestoController@editarDescuento')->middleware('necesitaPermisos:liquidacion-impuesto-editar-descuento');

            Route::post('editarDescuento', 'ImpuestoController@actualizarDescuento')->middleware('necesitaPermisos:liquidacion-impuesto-editar-descuento');

            Route::get('calcularValores/{vehiculoId}/{vigenciaId}', 'ImpuestoController@calcularValores');

            Route::post('nuevaLiquidacion', 'ImpuestoController@crearLiquidacion')->middleware('necesitaPermisos:liquidacion-impuesto-crear');

            Route::get('imprimirLiquidacion/{id}','ImpuestoController@imprimirLiquidacion');

            Route::get('importarBasesGravables','ImpuestoController@importarBasesGravablesF1')->middleware('necesitaPermisos:liquidacion-impuesto-importar-base-gravable');

            Route::post('importarBasesGravables','ImpuestoController@importarBasesGravablesF2')->middleware('necesitaPermisos:liquidacion-impuesto-importar-base-gravable');

            Route::get('registrarPago/{id}', 'ImpuestoController@registrarPagoF1')->middleware('necesitaPermisos:liquidacion-impuesto-registrar-pago');

            Route::post('registrarPago', 'ImpuestoController@registrarPagoF2')->middleware('necesitaPermisos:liquidacion-impuesto-registrar-pago');

            Route::get('verPago/{id}', 'ImpuestoController@verPago');

            Route::get('verConsignacion/{id}', 'ImpuestoController@verConsignacion');

            Route::get('editarPago/{id}', 'ImpuestoController@editarPagoF1')->middleware('necesitaPermisos:liquidacion-impuesto-editar-pago');

            Route::post('editarPago', 'ImpuestoController@editarPagoF2')->middleware('necesitaPermisos:liquidacion-impuesto-editar-pago');

            Route::get('reCalcularLiquidacion/{id}','ImpuestoController@reCalcularLiquidacionF1')->middleware('necesitaPermisos:liquidacion-impuesto-recalcular');

            Route::post('reCalcularLiquidacion','ImpuestoController@reCalcularLiquidacionF2')->middleware('necesitaPermisos:liquidacion-impuesto-recalcular');

            Route::get('importarRegistros', function(){
                return view('admin.tramites.impuestos.importarRegistros')->render();
            })->middleware('necesitaPermisos:liquidacion-impuesto-importar-registros');

            Route::post('importarRegistros', 'ImpuestoController@importarRegistros')->middleware('necesitaPermisos:liquidacion-impuesto-importar-registros');
    
            Route::get('obtenerClasesGrupos', 'ImpuestoController@obtenerClasesGrupos');

            Route::get('nuevaClaseGrupo','ImpuestoController@nuevaClaseGrupo')->middleware('necesitaPermisos:liquidacion-impuesto-crear-clase-grupo');

            Route::post('crearClaseGrupo','ImpuestoController@crearClaseGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-crear-clase-grupo');

            Route::get('editarClaseGrupo/{id}','ImpuestoController@editarClaseGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-editar-clase-grupo');

            Route::post('actualizarClaseGrupo','ImpuestoController@actualizarClaseGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-editar-clase-grupo');

            Route::get('obtenerCilindrajesGrupos', 'ImpuestoController@obtenerCilindrajesGrupos');

            Route::get('nuevoCilindrajeGrupo','ImpuestoController@nuevoCilindrajeGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-crear-cilindraje-grupo');

            Route::post('crearCilindrajeGrupo','ImpuestoController@crearCilindrajeGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-crear-cilindraje-grupo');

            Route::get('editarCilindrajeGrupo/{id}','ImpuestoController@editarCilindrajeGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-editar-cilindraje-grupo');

            Route::post('actualizarCilindrajeGrupo','ImpuestoController@actualizarCilindrajeGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-editar-cilindraje-grupo');

            Route::get('obtenerBateriasGrupos', 'ImpuestoController@obtenerBateriasGrupos');

            Route::get('nuevaBateriaGrupo','ImpuestoController@nuevaBateriaGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-crear-bateria-grupo');

            Route::post('crearBateriaGrupo','ImpuestoController@crearBateriaGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-crear-bateria-grupo');

            Route::get('editarBateriaGrupo/{id}','ImpuestoController@editarBateriaGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-editar-bateria-grupo');

            Route::post('actualizarBateriaGrupo','ImpuestoController@actualizarBateriaGrupo')->middleware( 'necesitaPermisos:liquidacion-impuesto-editar-bateria-grupo');

        });

        Route::group(['prefix' => 'tramites', 'middleware' => 'necesitaPermisos:tramite-administrar'], function () {

            Route::get('obtenerTramites/{page?}', 'TramiteController@obtenerTramites');

            Route::get('administrar', 'TramiteController@administrar');

            Route::get('eliminarTramite/{id}', 'TramiteController@eliminarTramite')->middleware('necesitaPermisos:tramite-eliminar');

            Route::get('restaurarTramite/{id}', 'TramiteController@restaurarTramite')->middleware('necesitaPermisos:tramite-restaurar');

            Route::get('editarTramite/{id}', 'TramiteController@editarTramite')->middleware('necesitaPermisos:tramite-editar');

            Route::post('crearTramite', 'TramiteController@crearTramite')->middleware('necesitaPermisos:tramite-crear');

            Route::post('actualizarTramite', 'TramiteController@actualizarTramite')->middleware('necesitaPermisos:tramite-editar');

            Route::get('nuevoTramite', 'TramiteController@nuevoTramite')->middleware('necesitaPermisos:tramite-crear');

            Route::get('administrarRequerimientos/{tramiteId}', 'TramiteController@administrarRequerimientos');

            Route::post('crearRequerimiento', 'TramiteController@crearRequerimiento')->middleware('necesitaPermisos:tramite-requerimiento-crear');

            Route::get('obtenerRequerimientos/{tramiteId}','TramiteController@obtenerRequerimientos');

            Route::get('editarRequerimiento/{id}', 'TramiteController@editarRequerimiento')->middleware('necesitaPermisos:tramite-requerimiento-editar');

            Route::post('editarRequerimiento', 'TramiteController@actualizarRequerimiento')->middleware('necesitaPermisos:tramite-requerimiento-editar');

            Route::post('obtenerRequerimientosTramites', 'TramiteController@obtenerRequerimientosTramites');

        });

        Route::group(['prefix' => 'tramitesGrupos', 'middleware' => 'necesitaPermisos:tramite-grupo-administrar'], function () {

            Route::get('obtenerGrupos/{page?}', 'TramiteGrupoController@obtenerGrupos');

            Route::get('administrar', 'TramiteGrupoController@administrar');

            Route::get('eliminarGrupo/{id}', 'TramiteGrupoController@eliminarGrupo')->middleware('necesitaPermisos:tramite-grupo-eliminar');

            Route::get('restaurarGrupo/{id}', 'TramiteGrupoController@restaurarGrupo')->middleware('necesitaPermisos:tramite-grupo-restaurar');

            Route::get('editarGrupo/{id}', 'TramiteGrupoController@editarGrupo')->middleware('necesitaPermisos:tramite-grupo-editar');

            Route::post('crearGrupo', 'TramiteGrupoController@crearGrupo')->middleware('necesitaPermisos:tramite-grupo-crear');

            Route::post('actualizarGrupo', 'TramiteGrupoController@actualizarGrupo')->middleware('necesitaPermisos:tramite-grupo-editar');

            Route::get('nuevoGrupo', 'TramiteGrupoController@nuevoGrupo')->middleware('necesitaPermisos:tramite-grupo-crear');

            Route::get('obtenerTramites/{id}', 'TramiteGrupoController@obtenerTramites');

        });

    });
    /*
     * Reportes
     */
    Route::group(['prefix' => 'reportes'], function () {

        Route::group(['prefix' => 'archivo'], function () {

            Route::get('SolicitudesPorDias', 'ReporteController@archivo_SolicitudesPorDias');

            Route::get('SolicitudesPorMeses', 'ReporteController@archivo_SolicitudesPorMeses');

            Route::get('SolicitudesPorTramites', 'ReporteController@archivo_SolicitudesPorTramites');

            Route::get('SeriesRegistradas', 'ReporteController@archivo_SeriesRegistradas');

            Route::get('CarpetasTotales', 'ReporteController@archivo_CarpetasTotales');

            Route::get('CarpetasPorEstado', 'ReporteController@archivo_CarpetasPorEstado');

            Route::get('CarpetasPorClaseVehiculo', 'ReporteController@archivo_CarpetasPorClaseVehiculo');

            Route::get('CarpetasPorFuera', 'ReporteController@archivo_CarpetasPorFuera');

            Route::get('CarpetasPorMeses', 'ReporteController@archivo_CarpetasPorMeses');

            Route::get('CarpetasPorDias', 'ReporteController@archivo_CarpetasPorDias');

        });

        Route::group(['prefix' => 'inspeccion'], function () {

            Route::get('TOSProximasAVencer', 'ReporteController@inspeccion_TOSProximasAVencer');

            Route::get('TOSVencidas', 'ReporteController@inspeccion_TOSVencidas');

            Route::get('TOPorNivelDeServicio', 'ReporteController@inspeccion_TOPorNivelDeServicio');

            Route::get('TOActivasPorEmpresa', 'ReporteController@inspeccion_TOActivasPorEmpresa');

            Route::get('TOExpedidasActualVigencia', 'ReporteController@inspeccion_TOExpedidasActualVigencia');

            Route::get('ComparendosPorAñosYMeses', 'ReporteController@inspeccion_ComparendosPorAñosYMeses');

            Route::get('ComparendosPorTipos', 'ReporteController@inspeccion_ComparendosPorTipos');

            Route::get('SancionesPorAñosYMeses', 'ReporteController@sancionesPorAñosYMeses');

            Route::get('SancionesPorTiposYAños', 'ReporteController@sancionesPorTiposYAños');

        });

        Route::group(['prefix' => 'cobrocoactivo'], function () {

            Route::get('FotoMultasPorAñosYMeses', 'ReporteController@cobrocoactivo_FotoMultasPorAñosYMeses');

            Route::get('ComparendosPorAñosYMeses', 'ReporteController@cobrocoactivo_ComparendosPorAñosYMeses');
        });

        Route::group(['prefix' => 'tramites'], function () {

            Route::get('SolicitudesTramitesPorAñosYMeses', 'ReporteController@SolicitudesTramitesPorAñosYMeses');

            Route::get('SolicitudesTramitesPorDias', 'ReporteController@SolicitudesTramitesPorDias');

            Route::get('SolicitudesTramitesPorTramites', 'ReporteController@SolicitudesTramitesPorTramites');

            Route::get('SolicitudesTramitesPorEstadosAsignados', 'ReporteController@SolicitudesTramitesPorEstadosAsignados');

            Route::get('SolicitudesTramitesTurnosGenerados', 'ReporteController@SolicitudesTramitesTurnosGenerados');

            Route::get('SolicitudesTramitesTurnosPreferentes', 'ReporteController@SolicitudesTramitesTurnosPreferentes');

            Route::get('SolicitudesTramitesTurnosAnulados', 'ReporteController@SolicitudesTramitesTurnosAnulados');

            Route::get('SolicitudesTramitesTurnosVencidos', 'ReporteController@SolicitudesTramitesTurnosVencidos');

            Route::get('SolicitudesTramitesTurnosReLlamados', 'ReporteController@SolicitudesTramitesTurnosReLlamados');

            Route::get('SolicitudesTramitesTurnosPorOrigen', 'ReporteController@SolicitudesTramitesTurnosPorOrigen');

            Route::get('SolicitudesTramitesPorEstado', 'ReporteController@SolicitudesTramitesPorEstado');

            Route::get('SustratosConsumidosPorAños', 'ReporteController@SustratosConsumidosPorAños');

            Route::get('SustratosConsumidosPorMeses', 'ReporteController@SustratosConsumidosPorMeses');

            Route::get('SustratosConsumidosPorDias', 'ReporteController@SustratosConsumidosPorDias');

            Route::get('SustratosPorTipo', 'ReporteController@SustratosPorTipo');

            Route::get('PlacasConsumidasPorAños', 'ReporteController@PlacasConsumidasPorAños');

            Route::get('PlacasConsumidasPorMeses', 'ReporteController@PlacasConsumidasPorMeses');

            Route::get('PlacasConsumidasPorDias', 'ReporteController@PlacasConsumidasPorDias');

            Route::get('PlacasPorServicioVehiculo', 'ReporteController@PlacasPorServicioVehiculo');

            Route::get('VentanillaFuncionarioSolicitudesAtendidasPorAñosYMeses','ReporteController@ventanillaFuncionarioSolicitudesAtentidadasPorAñosYMeses');

            Route::get('VentanillaSolicitudesAtendidasPorAñosYMeses/{ventanillaId}','ReporteController@ventanillaSolicitudesAtendidasPorAñosYMeses');

            Route::get('VentanillaYFuncionarioSolicitudesAtentidadasPorAñosYMeses/{ventanillaId}','ReporteController@ventanillaYFuncionarioSolicitudesAtendidasPorAñosYMeses');

        });

        Route::group(['prefix' => 'preAsignaciones'], function () {

            Route::get('PorAñosyMeses', 'ReporteController@PreAsignacionesPorAñosMeses');

            Route::get('ClasesVehiculos', 'ReporteController@PreAsignacionesPorClaseVehiculo');

            Route::get('ServiciosVehiculos', 'ReporteController@PreAsignacionesPorServicioVehiculo');

        });

        Route::group(['prefix' => 'user'], function () {

            Route::get('MisSolicitudesPorMeses', 'ReporteController@solicitudes_MisSolicitudesPorMeses');

            Route::get('MisSolicitudesPorDias', 'ReporteController@solicitudes_MisSolicitudesPorDias');

            Route::get('MisSolicitudesAprobadas', 'ReporteController@solicitudes_MisSolicitudesAprobadas');

            Route::get('MisSolicitudesRechazadas', 'ReporteController@solicitudes_MisSolicitudesRechazadas');

            Route::get('MisSolicitudesPendientes', 'ReporteController@solicitudes_MisSolicitudesPendientes');

            Route::get('MisSolicitudesUltimaSemana', 'ReporteController@solicitudes_MisSolicitudesUltimaSemana');

            Route::get('CarpetasRecibidas', 'ReporteController@solicitudes_CarpetasRecibidas');

            Route::get('CarpetasRecibidasPorMeses', 'ReporteController@solicitudes_CarpetasRecibidasPorMeses');

            Route::get('CarpetasRecibidasPorDias', 'ReporteController@solicitudes_CarpetasRecibidasPorDias');

            Route::get('CarpetasRecibidasUltimaSemana', 'ReporteController@solicitudes_CarpetasRecibidasUltimaSemana');
        });

        Route::group(['prefix' => 'gestionSolicitudes'], function () {

            Route::get('SolicitudesAprobadasPorDias', 'ReporteController@gestionSolicitudes_SolicitudesAprobadasPorDias');

            Route::get('SolicitudesAprobadasPorMeses', 'ReporteController@gestionSolicitudes_SolicitudesAprobadasPorMeses');

            Route::get('SolicitudesAprobadasUltimaSemana', 'ReporteController@gestionSolicitudes_SolicitudesAprobadasUltimaSemana');

            Route::get('SolicitudesDenegadasPorDias', 'ReporteController@gestionSolicitudes_SolicitudesDenegadasPorDias');

            Route::get('SolicitudesDenegadasPorMeses', 'ReporteController@gestionSolicitudes_SolicitudesDenegadasPorMeses');

            Route::get('SolicitudesDenegadasUltimaSemana', 'ReporteController@gestionSolicitudes_SolicitudesDenegadasUltimaSemana');

            Route::get('SolicitudesSinEntregar', 'ReporteController@gestionSolicitudes_SolicitudesSinEntregar');

            Route::get('CarpetasSinDevolver', 'ReporteController@gestionSolicitudes_CarpetasSinDevolver');

            Route::get('CarpetasSinValidar', 'ReporteController@gestionSolicitudes_CarpetasSinValidar');

            Route::get('CarpetasValidadasPorEstado', 'ReporteController@gestionSolicitudes_CarpetasValidadasPorEstado');

            Route::get('SolicitudesDenegadasPorMotivo', 'ReporteController@gestionSolicitudes_SolicitudesDenegadasPorMotivo');
        });

        Route::group(['prefix' => 'pqr'], function () {

            Route::get('respondidasATiempo', 'ReporteController@pqr_respondidasATiempo');

            Route::get('respondidasFueraTiempo', 'ReporteController@pqr_respondidasFueraTiempo');

            Route::get('sinResponder', 'ReporteController@pqr_sinResponder');

            Route::get('vencidas', 'ReporteController@pqr_vencidas');

            Route::get('porVencer', 'ReporteController@pqr_porVencer');

            Route::get('pqrPorClases', 'ReporteController@pqr_pqrPorClases');

            Route::get('CoExPorAñosYMeses', 'ReporteController@pqr_CoExPorAñosYMeses');

            Route::get('CoInPorAñosYMeses', 'ReporteController@pqr_CoInPorAñosYMeses');

            Route::get('CoSaPorAñosYMeses', 'ReporteController@pqr_CoSaPorAñosYMeses');

            Route::get('GeneralPorAñosYMeses', 'ReporteController@pqr_GeneralPorAñosYMeses');

            Route::get('pqrPorMedioTraslado', 'ReporteController@pqr_PorMedioTraslado');

            Route::get('CoExAnuladasPorAñosYMeses', 'ReporteController@pqr_CoExAnuladasPorAñosYMeses');

            Route::get('CoInAnuladasPorAñosYMeses', 'ReporteController@pqr_CoInAnuladasPorAñosYMeses');

            Route::get('CoSaAnuladasPorAñosYMeses', 'ReporteController@pqr_CoSaAnuladasPorAñosYMeses');

            Route::get('GeneralAnuladasPorAñosYMeses', 'ReporteController@pqr_GeneralAnuladasPorAñosYMeses');

            Route::get('misPQR_asignadasGeneralCoEx', 'ReporteController@misPQR_asignadasGeneralCoEx');

            Route::get('misPQR_asignadasGeneralCoIn', 'ReporteController@misPQR_asignadasGeneralCoIn');

            Route::get('misPQRRadicadasGeneralPorTipo', 'ReporteController@misPQR_radicadasGeneralPorTipo');

            Route::get('misPQRRespondidasGeneralCoEx', 'ReporteController@misPQR_respondidasGeneralCoEx');

            Route::get('misPQRRespondidasGeneralCoIn', 'ReporteController@misPQR_respondidasGeneralCoIn');

            Route::get('misPQR_asignadasClases', 'ReporteController@misPQR_asignadasClases');

            Route::get('misPQR_respondidasClases', 'ReporteController@misPQR_respondidasClases');

            Route::post('informeGeneralControlInterno', 'ReporteController@pqr_informeGeneralControlInterno');

        });

        Route::group(['prefix' => 'dependencias'], function () {

            Route::get('FuncionariosPorDependencia', 'ReporteController@dependencia_FuncionariosPorDependencia');

        });

        Route::group(['prefix' => 'empresasTransporte'], function () {

            Route::get('VehiculosPorEmpresaTransporte', 'ReporteController@empresaTransporte_VehiculosPorEmpresa');

            Route::get('TarjetasOperacionActivasPorEmpresa', 'ReporteController@empresaTransporte_TarjetasOperacionActivasPorEmpresa');

        });

        Route::group(['prefix' => 'roles'], function () {

            Route::get('FuncionariosPorRol', 'ReporteController@roles_FuncionariosPorRol');

        });

        Route::group(['prefix' => 'usuarios'], function () {

            Route::get('FuncionariosTwoFactor', 'ReporteController@usuarios_FuncionariosTwoFactor');

            Route::get('FuncionariosBloqueados', 'ReporteController@usuarios_FuncionariosBloqueados');

        });

        Route::group(['prefix' => 'vehiculos'], function () {

            Route::get('VehiculosPorMarca', 'ReporteController@vehiculos_VehiculosPorMarca');

            Route::get('VehiculosPorClase', 'ReporteController@vehiculos_VehiculosPorClase');

            Route::get('VehiculosPorCombustible', 'ReporteController@vehiculos_VehiculosPorCombustible');

            Route::get('VehiculosPorCarroceria', 'ReporteController@vehiculos_VehiculosPorCarroceria');

            Route::get('VehiculosPorNivelServicio', 'ReporteController@vehiculos_VehiculosPorNivelServicio');

            Route::get('VehiculosPorServicio', 'ReporteController@vehiculos_VehiculosPorServicio');

        });

    });

});

Route::get('posts/ultimasPublicaciones', 'HomeController@getLatestPost');

Route::get('posts', 'HomeController@getAllPosts');

Route::group(['prefix' => 'posts'], function () {

    Route::post('search', 'HomeController@getPostsByQuery');

    Route::get('tag/{tag}', 'HomeController@getPostsByTag');

    Route::get('date/{y}/{m}', 'HomeController@getPostsByYm');

    Route::get('{category}', 'HomeController@getPostsByCategory');

    Route::get('{category}/{post}', 'HomeController@getPostBySlug');

});

Route::group(['prefix' => 'servicios'], function () {    

    Route::group(['prefix' => 'liquidaciones'], function () {

        Route::group(['prefix' => 'servicioPublico'], function () {

            Route::get('index', 'ConsultasController@liquidacionesServicioPublico_index');

            Route::post('consultar', 'ConsultasController@liquidacionesServicioPublico_consultar');

            Route::post('nuevaLiquidacion', 'ConsultasController@liquidacionesServicioPublico_nuevaLiquidacion');

            Route::post('crearLiquidacion', 'ConsultasController@liquidacionesServicioPublico_crearLiquidacion');

            Route::post('calcularValores', 'ConsultasController@liquidacionesServicioPublico_calcularValores');

            Route::post('obtenerLiquidaciones', 'ConsultasController@liquidacionesServicioPublico_obtenerLiquidaciones');

            Route::get('imprimirLiquidacion/{id}', 'ConsultasController@liquidacionesServicioPublico_imprimirLiquidacion');

        });

        Route::group(['prefix' => 'acuerdoPago'], function () {

            Route::get('index', function(){
                return view('publico.liquidaciones.inspeccion.liquidarAcuerdosPago');
            });

        });

        Route::group(['prefix' => 'comparendos'], function () {

            Route::get('index', function(){
                return view('publico.liquidaciones.inspeccion.liquidarComparendos');
            });

        });

    });

    Route::group(['prefix' => 'turnos'], function () {

        Route::get('obtenerTurnosLlamados', 'ConsultasController@obtenerTurnosLlamados');

        Route::get('index', function () {
            return view('publico.turnos');
        });

    });

    Route::group(['prefix' => 'to'], function () {

        Route::post('consultar', 'ConsultasController@consultarTo');

        Route::get('index', 'ConsultasController@to_index');

    });

    Route::group(['prefix' => 'pqr'], function () {

        Route::post('procesar', 'PQRController@crearCoEx');

        Route::get('tipospqr', 'PQRController@obtenerTiposPQR');

        Route::get('tiposoficios', 'PQRController@obtenerTiposOficios');

        Route::get('index', function () {
            return view('publico.pqr.index');
        });

        Route::get('pdf/{id}', 'ConsultasController@pqr_getDocumento');

        Route::get('radicar', 'PQRController@radicar');

        Route::get('estado', 'ConsultasController@consultarEstadoPQR');

        Route::post('consultar', 'ConsultasController@consultarProcesoPQR');

        Route::get('respuesta/get/anexos/{id}', 'ConsultasController@pqr_getAnexos');

    });

    Route::group(['prefix' => 'consultas'], function () {

        Route::get('obtenerCiudadesDpto/{id}', function ($id) {
            return \App\ciudad::where('departamento_id', $id)->orderBy('name', 'asc')->pluck('name', 'id');
        });

    });

    Route::group(['prefix' => 'inspeccion'], function () {

        Route::get('index', function () {
            return view('publico.inspeccion.consultarNotificaciones');
        });

    });

    Route::group(['prefix' => 'normativas'], function () {

        Route::get('index', function () {
            return view('publico.normativas.consultarNormativas');
        });

        Route::get('normativas', 'HomeController@getNormativas');

        Route::post('consultar', 'ConsultasController@consultarNormativas');

        Route::get('documento/{id}', 'ConsultasController@getDocumentoNormativa');

        Route::get('exportar/{parametro}', 'ConsultasController@exportarNormativas');

    });

    Route::group(['prefix' => 'notificacionesAviso'], function () {

        Route::get('index', function () {
            return view('publico.notificacionesAviso.consultarNotificacionesAviso');
        });

        Route::get('notificacionesAviso', 'HomeController@getNotificacionesAvisoPresenteAnio');

        Route::post('consultarNotificacionesAviso', 'ConsultasController@consultarNotificacionesAviso');

        Route::get('documento/{id}', 'ConsultasController@getDocumentoNotificacionAviso');

        Route::get('exportar/{parametro}', 'ConsultasController@exportarNotificacionesAviso');
        
    });

    Route::group(['prefix' => 'tramites'], function () {

        Route::group(['prefix' => 'preasignaciones'], function () {

            Route::get('consultar', function () {
                return view('publico.tramites.pre_asignaciones.consultar');
            });

            Route::post('consultar', 'ConsultasController@consultarPreAsignacion');

            Route::get('imprimir/{id}', 'ConsultasController@imprimirPreAsignacion');

            Route::get('solicitar', 'ConsultasController@solicitarPreAsignacion');

            Route::post('crearSolicitudPreAsignacion', 'ConsultasController@crearSolicitudPreAsignacion');

            Route::get('serviciosPorClase/{id}', 'ConsultasController@getServiciosPorClaseVehiculo');

            Route::get('index', function () {
                return view('publico.tramites.pre_asignaciones.index', ['tipocriterio' => null]);
            });

        });

    });

});





