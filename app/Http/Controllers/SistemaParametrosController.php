<?php

namespace App\Http\Controllers;

use anlutro\LaravelSettings\Facade as Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use App\sistema_parametros_empresa;
use App\sistema_parametros_gd;
use App\sistema_parametros_pqr;
use App\sistema_parametros_to;
use App\sistema_parametros_tramites;
use App\sistema_parametros_vigencia;
use Validator;

class SistemaParametrosController extends Controller
{
    public function empresa_administrar()
    {
        $filtros = [
            '1' => 'Vigencia',
            '2' => 'Nombre',
        ];

        $sFiltro = null;

        return view('admin.sistema.parametros.empresa.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function empresa_obtenerFirma($id)
    {
        $empresa = sistema_parametros_empresa::find($id);
        try{
            if(\File::exists( $image=storage_path('app/otros/empresa/'.$empresa->firma_director) )){
                return \Image::make($image)->response('jpg');
            }else{
                abort(404);
            }  
        }catch(\Exception $e){

        }              
    }

    public function empresa_obtenerFirmaInspector($id)
    {
        $empresa = sistema_parametros_empresa::find($id);
        try{
            if(\File::exists( $image=storage_path('app/otros/empresa/'.$empresa->firma_inspector) )){
                return \Image::make($image)->response('jpg');
            }else{
                abort(404);
            }  
        }catch(\Exception $e){

        }              
    }

    public function empresa_obtenerRegistros()
    {
        $registros = sistema_parametros_empresa::paginate(30);
        return view('admin.sistema.parametros.empresa.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function empresa_nuevoRegistro()
    {
        $vigencias = sistema_parametros_vigencia::doesntHave('hasEmpresa')->pluck('vigencia','id');
        if($vigencias->count() <= 0){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Todas las vigencias ya tienen un registro de empresa. Deberá modificar el registro existente.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
        return view('admin.sistema.parametros.empresa.nuevoRegistro', ['vigencias'=>$vigencias])->render();
    }

    public function empresa_crearRegistro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'string|required',
            'logo_menu' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'logo' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'header' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'map_coordinates' => 'required|string',
            'vigencia' => ['required','integer','exists:mysql_system.vigencia,id',Rule::unique('mysql_system.parametros_empresa','vigencia_id')->ignore($request->registro_id)],
            'nombre_director' => 'required|string',
            'firma_director' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'sigla' => 'string|required',
            'direccion' => 'string|required',
            'telefono' => 'string|required',
            'web' => 'string|required',
            'correo' => 'email|required',
            'correo_administrador' => 'email|required',
            'descripcion' => 'required|string',
            'horario' => 'required|string',
            'facebook' => 'required|string',
            'twitter' => 'required|string',
            'nombre_inspector' => 'required|string',
            'firma_inspector' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
        ], [
            'nombre.string' => 'El nombre especificado no tiene un formato válido.',
            'nombre.required' => 'No se ha especificado el nombre de la empresa.',
            'logo_menu.mimes' => 'El logo de menú especificado no tiene un formato válido.',
            'logo_menu.mimetypes' => 'El logo de menú especificado no tiene un formato válido.',
            'logo_menu.max' => 'El tamaño del logo de menú especificado excede el máximo permitido de 2MB.',
            'logo.mimes' => 'El logo de la empresa especificado no tiene un formato válido.',
            'logo.mimetypes' => 'El logo de la empresa especificado no tiene un formato válido.',
            'logo.max' => 'El tamaño del logo de la empresa especificado excede el máximo permitido de 2MB.',
            'header.mimes' => 'La imagen de encabezado para las páginas públicas especificada no tiene un formato válido.',
            'header.mimetypes' => 'La imagen de encabezado para las páginas públicas especificada no tiene un formato válido.',
            'header.max' => 'El tamaño de la imagen de encabezado para las páginas públicas especificada excede el máximo permitido de 2MB;',
            'map_coordinates.required' => 'No se ha especificado las coordenadas de ubicación para el mapa de google maps.',
            'map_coordinates.string' => 'Las coordenadas especificadas no tienen un formato válido.',
            'vigencia.required' => 'No se ha especificado la vigencia a la que pertenece este registro.',
            'vigencia.integer' => 'La vigencia especificada no tiene un formato válido.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'nombre_director.required' => 'No se ha especificado el nombre del director.',
            'nombre_director.string' => 'El nombre del director no tiene un formato válido.',
            'firma_director.mimes' => 'La firma especificada no tiene un formato válido.',
            'firma_director.mimetypes' => 'La firma especificada no tiene un formato válido.',
            'firma_director.max' => 'El tamaño de la firma excede el máximo permitido de 2MB.',
            'sigla.string' => 'La sigla especificada no tiene un formato válido.',
            'sigla.required' => 'No se ha especificado la sigla del nombre.',
            'direccion.string' => 'La dirección especificada no tiene un formato válido.',
            'direccion.required' => 'No se ha especificado la dirección.',
            'telefono.string' => 'El teléfono especificado no tiene un formato válido.',
            'telefono.required' => 'No se ha especificado el teléfono.',
            'web.string' => 'La página web especificada no tiene un formato válido.',
            'web.required' => 'No se ha especificado la página web.',
            'correo.email' => 'El correo de contacto especificado no tiene un formato válido.',
            'correo.required' => 'No se ha especificado el correo de contacto.',
            'correo_administrador.email' => 'El correo del administrador especificado no tiene un formato válido.',
            'correo_administrador.required' => 'No se ha especificado el correo del administrador.',
            'descripcion.required' => 'No se ha especificado la descripción de la empresa.',
            'descripcion.string' => 'La descripción de la empresa especificada no tiene un formato válido.',
            'horario.required' => 'No se ha especificado el horario.',
            'horario.string' => 'El horario especificado no tiene un formato válido.',
            'facebook.required' => 'No se ha especificado el username en Facebook de la empresa.',
            'facebook.string' => 'El username de Facebook especificado no tiene un formato válido.',
            'twitter.required' => 'No se ha especificado el username en Twitter de la empresa.',
            'twitter.string' => 'El username de Twitter especificado no tiene un formato válido.',
            'nombre_inspector.required' => 'No se ha especificado el nombre del director.',
            'nombre_inspector.string' => 'El nombre del director no tiene un formato válido.',
            'firma_inspector.mimes' => 'La firma especificada no tiene un formato válido.',
            'firma_inspector.mimetypes' => 'La firma especificada no tiene un formato válido.',
            'firma_inspector.max' => 'El tamaño de la firma excede el máximo permitido de 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }

        try{
            $registro = new sistema_parametros_empresa();
            $registro->empresa_nombre = strtoupper($request->nombre);
            $registro->empresa_map_coordinates = $request->map_coordinates;
            $registro->vigencia_id = $request->vigencia;
            $registro->nombre_director = strtoupper($request->nombre_director);
            $registro->empresa_sigla = $request->sigla;
            $registro->empresa_direccion = $request->direccion;
            $registro->empresa_telefono = $request->telefono;
            $registro->empresa_web = $request->web;
            $registro->empresa_correo_contacto = $request->correo;
            $registro->correo_administrador = $request->correo_administrador;
            $registro->descripcion = $request->descripcion;
            $registro->horario = $request->horario;
            $registro->keywords = $request->keywords;
            $registro->nombre_inspector = $request->nombre_inspector;
            $registro->facebook = $request->facebook;
            $registro->twitter = $request->twitter;

            if($request->logo_menu != null){
                $registro->empresa_logo_menu = $registro->id.'_logo_menu.jpg';
                \Storage::disk('parametros')->putFileAs('empresa', $request->logo_menu, $registro->id.'_logo_menu.jpg');
            }

            if($request->logo != null){
                $registro->empresa_logo = $registro->id.'_logo.jpg';
                \Storage::disk('parametros')->putFileAs('empresa', $request->logo, $registro->id.'_logo.jpg');
            }

            if($request->header != null){
                $registro->empresa_header = $registro->id.'_header.jpg';
                \Storage::disk('parametros')->putFileAs('empresa', $request->header, $registro->id.'_header.jpg');
            }

            if($request->firma_director != null){
                $registro->firma_director = $registro->id.'_firma_director.jpg';
                \Storage::disk('local')->putFileAs('otros/empresa', $request->firma_director, $registro->id.'_firma_director.jpg');
                chmod(storage_path('app/otros/empresa/'.$registro->firma_director), 0640);
            }

            if($request->firma_inspector != null){
                $registro->firma_inspector = $registro->id.'_firma_inspector.jpg';
                \Storage::disk('local')->putFileAs('otros/empresa', $request->firma_inspector, $registro->id.'_firma_inspector.jpg');
                chmod(storage_path('app/otros/empresa/'.$registro->firma_inspector), 0640);
            }

            $registro->save();                

            return response()->view('admin.mensajes.success', [
                'mensaje' => 'Se ha registrado al información de la empresa.',
                'encabezado' => '¡Completado!',
            ], 200);
        }catch (\Exception $e){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                'encabezado' => 'Error en el proceso',
            ], 200);
        }
        
    }

    public function empresa_editarRegistro($id)
    {
        $vigencias = sistema_parametros_vigencia::pluck('vigencia', 'id');
        $registro = sistema_parametros_empresa::find($id);
        return view('admin.sistema.parametros.empresa.editarRegistro', ['vigencias'=>$vigencias, 'registro'=>$registro])->render();
    }

    public function empresa_guardarCambios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registro_id' => 'required|integer|exists:mysql_system.parametros_empresa,id',
            'nombre' => 'string|required',
            'logo_menu' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'logo' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'header' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'map_coordinates' => 'required|string',
            'vigencia' => ['required','integer','exists:mysql_system.vigencia,id',Rule::unique('mysql_system.parametros_empresa','vigencia_id')->ignore($request->registro_id)],
            'nombre_director' => 'required|string',
            'firma_director' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'sigla' => 'string|required',
            'direccion' => 'string|required',
            'telefono' => 'string|required',
            'web' => 'string|required',
            'correo' => 'email|required',
            'correo_administrador' => 'email|required',
            'descripcion' => 'required|string',
            'horario' => 'required|string',
            'facebook' => 'required|string',
            'twitter' => 'required|string',
            'nombre_inspector' => 'required|string',
            'firma_inspector' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
        ], [
            'registro_id.required' => 'No se ha especificado el registro a modificar.',
            'registro_id.integer' => 'El ID del registro especificado no tiene un formato válido.',
            'registro_id.exists' => 'El registro especificado no existe en el sistema.',
            'nombre.string' => 'El nombre especificado no tiene un formato válido.',
            'nombre.required' => 'No se ha especificado el nombre de la empresa.',
            'logo_menu.mimes' => 'El logo de menú especificado no tiene un formato válido.',
            'logo_menu.mimetypes' => 'El logo de menú especificado no tiene un formato válido.',
            'logo_menu.max' => 'El tamaño del logo de menú especificado excede el máximo permitido de 2MB.',
            'logo.mimes' => 'El logo de la empresa especificado no tiene un formato válido.',
            'logo.mimetypes' => 'El logo de la empresa especificado no tiene un formato válido.',
            'logo.max' => 'El tamaño del logo de la empresa especificado excede el máximo permitido de 2MB.',
            'header.mimes' => 'La imagen de encabezado para las páginas públicas especificada no tiene un formato válido.',
            'header.mimetypes' => 'La imagen de encabezado para las páginas públicas especificada no tiene un formato válido.',
            'header.max' => 'El tamaño de la imagen de encabezado para las páginas públicas especificada excede el máximo permitido de 2MB;',
            'map_coordinates.required' => 'No se ha especificado las coordenadas de ubicación para el mapa de google maps.',
            'map_coordinates.string' => 'Las coordenadas especificadas no tienen un formato válido.',
            'vigencia.required' => 'No se ha especificado la vigencia a la que pertenece este registro.',
            'vigencia.integer' => 'La vigencia especificada no tiene un formato válido.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'nombre_director.required' => 'No se ha especificado el nombre del director.',
            'nombre_director.string' => 'El nombre del director no tiene un formato válido.',
            'firma_director.mimes' => 'La firma especificada no tiene un formato válido.',
            'firma_director.mimetypes' => 'La firma especificada no tiene un formato válido.',
            'firma_director.max' => 'El tamaño de la firma excede el máximo permitido de 2MB.',
            'sigla.string' => 'La sigla especificada no tiene un formato válido.',
            'sigla.required' => 'No se ha especificado la sigla del nombre.',
            'direccion.string' => 'La dirección especificada no tiene un formato válido.',
            'direccion.required' => 'No se ha especificado la dirección.',
            'telefono.string' => 'El teléfono especificado no tiene un formato válido.',
            'telefono.required' => 'No se ha especificado el teléfono.',
            'web.string' => 'La página web especificada no tiene un formato válido.',
            'web.required' => 'No se ha especificado la página web.',
            'correo.email' => 'El correo de contacto especificado no tiene un formato válido.',
            'correo.required' => 'No se ha especificado el correo de contacto.',
            'correo_administrador.email' => 'El correo del administrador especificado no tiene un formato válido.',
            'correo_administrador.required' => 'No se ha especificado el correo del administrador.',
            'descripcion.required' => 'No se ha especificado la descripción de la empresa.',
            'descripcion.string' => 'La descripción de la empresa especificada no tiene un formato válido.',
            'horario.required' => 'No se ha especificado el horario.',
            'horario.string' => 'El horario especificado no tiene un formato válido.',
            'facebook.required' => 'No se ha especificado el username en Facebook de la empresa.',
            'facebook.string' => 'El username de Facebook especificado no tiene un formato válido.',
            'twitter.required' => 'No se ha especificado el username en Twitter de la empresa.',
            'twitter.string' => 'El username de Twitter especificado no tiene un formato válido.',
            'nombre_inspector.required' => 'No se ha especificado el nombre del director.',
            'nombre_inspector.string' => 'El nombre del director no tiene un formato válido.',
            'firma_inspector.mimes' => 'La firma especificada no tiene un formato válido.',
            'firma_inspector.mimetypes' => 'La firma especificada no tiene un formato válido.',
            'firma_inspector.max' => 'El tamaño de la firma excede el máximo permitido de 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            try{
                $registro = sistema_parametros_empresa::find($request->registro_id);
                $registro->empresa_nombre = strtoupper($request->nombre);
                $registro->empresa_map_coordinates = $request->map_coordinates;
                $registro->vigencia_id = $request->vigencia;
                $registro->nombre_director = strtoupper($request->nombre_director);
                $registro->empresa_sigla = $request->sigla;
                $registro->empresa_direccion = $request->direccion;
                $registro->empresa_telefono = $request->telefono;
                $registro->empresa_web = $request->web;
                $registro->empresa_correo_contacto = $request->correo;
                $registro->correo_administrador = $request->correo_administrador;
                $registro->descripcion = $request->descripcion;
                $registro->horario = $request->horario;
                $registro->keywords = $request->keywords;
                $registro->nombre_inspector = $request->nombre_inspector;
                $registro->facebook = $request->facebook;
                $registro->twitter = $request->twitter;

                if($request->logo_menu != null){
                    $registro->empresa_logo_menu = $registro->id.'_logo_menu.jpg';
                    \Storage::disk('parametros')->putFileAs('empresa', $request->logo_menu, $registro->id.'_logo_menu.jpg');
                }

                if($request->logo != null){
                    $registro->empresa_logo = $registro->id.'_logo.jpg';
                    \Storage::disk('parametros')->putFileAs('empresa', $request->logo, $registro->id.'_logo.jpg');
                }

                if($request->header != null){
                    $registro->empresa_header = $registro->id.'_header.jpg';
                    \Storage::disk('parametros')->putFileAs('empresa', $request->header, $registro->id.'_header.jpg');
                }

                if($request->firma_director != null){
                    $registro->firma_director = $registro->id.'_firma_director.jpg';
                    \Storage::disk('local')->putFileAs('otros/empresa', $request->firma_director, $registro->id.'_firma_director.jpg');
                    chmod(storage_path('app/otros/empresa/'.$registro->firma_director), 0640);
                }

                if($request->firma_inspector != null){
                    $registro->firma_inspector = $registro->id.'_firma_inspector.jpg';
                    \Storage::disk('local')->putFileAs('otros/empresa', $request->firma_inspector, $registro->id.'_firma_inspector.jpg');
                    chmod(storage_path('app/otros/empresa/'.$registro->firma_inspector), 0640);
                }

                $registro->save();                

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han realizado los cambios correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function empresa_filtrarRegistros(Request $request)
    {
        $registros = null;
        $parametro = $request->parametro;
        switch ($request->criterio)
        {
            case 1:
                $registros = sistema_parametros_empresa::with('hasVigencia')->whereHas('hasVigencia', function ($query) use ($parametro){
                    $query->vigencia = $parametro;
                })->get();
                break;
            case 2:
                $registros = sistema_parametros_empresa::with('hasVigencia')->where('empresa_nombre', 'like', '%'.$parametro.'%')->get();
                break;
        }
        return view('admin.sistema.parametros.empresa.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function pqr_administrar()
    {
        $filtros = [
            '1' => 'Vigencia'
        ];

        $sFiltro = null;

        return view('admin.sistema.parametros.pqr.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function pqr_obtenerRegistros()
    {
        $registros = sistema_parametros_pqr::paginate(30);

        return view('admin.sistema.parametros.pqr.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function pqr_nuevoRegistro()
    {
        $vigencias = sistema_parametros_vigencia::doesntHave('hasPQR')->pluck('vigencia','id');
        if($vigencias->count() <= 0){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Todas las vigencias ya tienen un registro de PQR. Deberá modificar el registro existente.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
        return view('admin.sistema.parametros.pqr.nuevoRegistro', ['vigencias'=>$vigencias])->render();
    }

    public function pqr_crearRegistro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vigencia' => ['required','integer','exists:mysql_system.vigencia,id',Rule::unique('mysql_system.parametros_pqr','vigencia_id')->ignore($request->registro_id)],
            'radicado_entrada' => 'required|numeric',
            'radicado_salida' => 'required|numeric',
            'editar_resuelto' => 'required|in:SI,NO',
            'previo_aviso' => 'required|integer',
            'logo_radicado' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000'
        ], [
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificado no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en la base de datos.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'radicado_entrada.required' => 'No se ha especificado el consecutivo inicial de los radicados de entrada.',
            'radicado_entrada.numeric' => 'El consecutivo inicial de los radicados de entrada no tiene un formato válido. Debe ser numérico.',
            'radicado_salida.required' => 'No se ha especificado el consecutivo inicial de los radicados desalida.',
            'radicado_salida.numeric' => 'El consecutivo inicial de los radicados de salida no tiene un formato válido. Debe ser numérico.',
            'editar_resuelto.required' => 'No se ha especificado si se permite editar los procesos PQR ya resueltos.',
            'editar_resuelto.in' => 'El valor especificado para editar procesos PQR ya resueltos no es válido. Debe ser SI o NO.',
            'previo_aviso.required' => 'No se ha especificado de cada cuantos días se comprobará el estado de los procesos PQr para dar previo aviso.',
            'previo_aviso.integer' => 'El valor especificado de cada cuantos días se comprobará el estado de los procesos PQr para dar previo aviso no tiene un formato válido.',
            'logo_radicado.mimes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'logo_radicado.mimetypes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'logo_radicado.max' => 'El tamaño del logo proporcionado para la etiqueta de radicado excede el máximo permitido de 2MB.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            try{
                $registro = new sistema_parametros_pqr();
                $registro->radicado_entrada_consecutivo = $request->radicado_entrada;
                $registro->radicado_salida_consecutivo = $request->radicado_salida;
                $registro->editar_pqr_resuelto = $request->editar_resuelto;
                $registro->dias_previo_aviso = $request->previo_aviso;
                $registro->vigencia_id = $request->vigencia;

                if($request->logo_radicado != null){
                    $registro->logo_pqr_radicado = $registro->id.'_logo_radicado.jpg';
                    \Storage::disk('parametros')->putFileAs('pqr', $request->logo_radicado, $registro->id.'_logo_radicado.jpg');
                }

                $registro->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado la información de PQR.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function pqr_editarRegistro($id)
    {
        $vigencias = sistema_parametros_vigencia::pluck('vigencia', 'id');
        $registro = sistema_parametros_pqr::find($id);
        return view('admin.sistema.parametros.pqr.editarRegistro', ['vigencias'=>$vigencias, 'registro'=>$registro])->render();
    }

    public function pqr_guardarCambios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registro_id' => 'required|integer|exists:mysql_system.parametros_pqr,id',
            'vigencia' => ['required','integer','exists:mysql_system.vigencia,id',Rule::unique('mysql_system.parametros_pqr','vigencia_id')->ignore($request->registro_id)],
            'radicado_entrada' => 'required|numeric',
            'radicado_salida' => 'required|numeric',
            'editar_resuelto' => 'required|in:SI,NO',
            'previo_aviso' => 'required|integer',
            'logo_radicado' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000'
        ], [
            'registro_id.required' => 'No se ha especificado el registro a modificar.',
            'registro_id.integer' => 'El ID del registro especificado no tiene un formato válido.',
            'registro_id.exists' => 'El registro especificado no existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificado no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en la base de datos.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'radicado_entrada.required' => 'No se ha especificado el consecutivo inicial de los radicados de entrada.',
            'radicado_entrada.numeric' => 'El consecutivo inicial de los radicados de entrada no tiene un formato válido. Debe ser numérico.',
            'radicado_salida.required' => 'No se ha especificado el consecutivo inicial de los radicados desalida.',
            'radicado_salida.numeric' => 'El consecutivo inicial de los radicados de salida no tiene un formato válido. Debe ser numérico.',
            'editar_resuelto.required' => 'No se ha especificado si se permite editar los procesos PQR ya resueltos.',
            'editar_resuelto.in' => 'El valor especificado para editar procesos PQR ya resueltos no es válido. Debe ser SI o NO.',
            'previo_aviso.required' => 'No se ha especificado de cada cuantos días se comprobará el estado de los procesos PQr para dar previo aviso.',
            'previo_aviso.integer' => 'El valor especificado de cada cuantos días se comprobará el estado de los procesos PQr para dar previo aviso no tiene un formato válido.',
            'logo_radicado.mimes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'logo_radicado.mimetypes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'logo_radicado.max' => 'El tamaño del logo proporcionado para la etiqueta de radicado excede el máximo permitido de 2MB.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            try{
                $registro = sistema_parametros_pqr::find($request->registro_id);
                $registro->radicado_entrada_consecutivo = $request->radicado_entrada;
                $registro->radicado_salida_consecutivo = $request->radicado_salida;
                $registro->editar_pqr_resuelto = $request->editar_resuelto;
                $registro->dias_previo_aviso = $request->previo_aviso;
                $registro->vigencia_id = $request->vigencia;

                if($request->logo_radicado != null){
                    $registro->logo_pqr_radicado = $registro->id.'_logo_radicado.jpg';
                    \Storage::disk('parametros')->putFileAs('pqr', $request->logo_radicado, $registro->id.'_logo_radicado.jpg');
                }

                $registro->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han realizado los cambios correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function pqr_filtrarRegistros(Request $request)
    {
        $registros = null;
        $parametro = $request->parametro;
        switch ($request->criterio)
        {
            case 1:
                $registros = sistema_parametros_pqr::with('hasVigencia')->whereHas('hasVigencia', function ($query) use ($parametro){
                    $query->vigencia = $parametro;
                })->get();
                break;
        }
        return view('admin.sistema.parametros.pqr.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function tramites_administrar()
    {
        $filtros = [
            '1' => 'Vigencia'
        ];

        $sFiltro = null;

        return view('admin.sistema.parametros.tramites.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function tramites_obtenerRegistros()
    {
        $registros = sistema_parametros_tramites::paginate(30);
        return view('admin.sistema.parametros.tramites.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function tramites_nuevoRegistro()
    {
        $vigencias = sistema_parametros_vigencia::doesntHave('hasTramite')->pluck('vigencia','id');
        if($vigencias->count() <= 0){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Todas las vigencias ya tienen un registro de Tramites. Deberá modificar el registro existente.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
        return view('admin.sistema.parametros.tramites.nuevoRegistro', ['vigencias'=>$vigencias])->render();
    }

    public function tramites_crearRegistro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vigencia' => ['required','integer','exists:mysql_system.vigencia,id',Rule::unique('mysql_system.parametros_tramites','vigencia_id')->ignore($request->registro_id)],
            'radicado_tramite' => 'required|numeric',
            'logo_turno' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'inicio_atencion_submit' => 'required|date_format:H:i',
            'fin_atencion_submit' => 'required|date_format:H:i',
            'habilita_turno_rellamado' => 'required|in:SI,NO',
            'habilita_turno_preferencial' => 'required|in:SI,NO',
            'habilitar_turno_transferencia' => 'required|in:SI,NO',
            'tiempo_espera_turno' => 'required|integer'
        ], [
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificado no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en la base de datos.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'radicado_tramite.required' => 'No se ha especificado el consecutivo inicial de los radicados de entrada.',
            'radicado_tramite.numeric' => 'El consecutivo inicial de los radicados de entrada no tiene un formato válido. Debe ser numérico.',
            'logo_turno.mimes' => 'El logo proporcionado para la etiqueta de turno no tiene un formato válido.',
            'logo_turno.mimetypes' => 'El logo proporcionado para la etiqueta de turno no tiene un formato válido.',
            'logo_turno.max' => 'El tamaño del logo proporcionado para la etiqueta de turno excede el máximo permitido de 2MB.',
            'inicio_atencion_submit.required' => 'No se ha especificado la hora de inicio de atención al público.',
            'inicio_atencion_submit.date_format' => 'La hora especificado de inicio de atención al público no tiene un formato válido.',
            'fin_atencion_submit.required' => 'No se ha especificado la hora de fin de atención al público.',
            'fin_atencion_submit.date_format' => 'La hora especificado de fin de atención al público no tiene un formato válido.',
            'habilita_turno_rellamado.in' => 'El valor especificado para habilitar el re-llamado de turnos no es válido.',
            'habilita_turno_rellamado.required' => 'No se ha especificado el valor para habilitar el re-llamado de turnos.',
            'habilita_turno_preferencial.required' => 'No se ha especificado el valor para habilitar turnos preferentes.',
            'habilita_turno_preferencial.in' => 'El valor especificado para habilitar turnos preferentes no es válido.',
            'habilitar_turno_transferencia.required' => 'No se ha especificado el valor para habilitar la transferencia de turnos.',
            'habilitar_turno_transferencia.in' => 'El valor especificado para habilitar la transferencia de turnos no es válido.',
            'tiempo_espera_turno.required' => 'No se ha especificado el tiempo de espera del turno después de llamado.',
            'tiempo_espera_turno.integer' => 'El valor especificado para el tiempo de espera del turno después de llamado no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            try{
                $registro = new sistema_parametros_tramites();
                $registro->radicado_tramite_consecutivo = $request->radicado_tramite;
                $registro->inicio_atencion = $request->inicio_atencion_submit;
                $registro->fin_atencion = $request->fin_atencion_submit;
                $registro->turno_rellamado = $request->habilita_turno_rellamado;
                $registro->turno_preferencial = $request->habilita_turno_preferencial;
                $registro->turno_transferencia = $request->habilitar_turno_transferencia;
                $registro->turno_tiempo_espera = $request->tiempo_espera_turno;
                $registro->vigencia_id = $request->vigencia;

                if($request->logo_turno != null){
                    $registro->turno_logo = $registro->id.'_logo_turno.jpg';
                    \Storage::disk('parametros')->putFileAs('tramites', $request->logo_turno, $registro->id.'_logo_turno.jpg');
                }

                $registro->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han realizado los cambios correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function tramites_editarRegistro($id)
    {
        $vigencias = sistema_parametros_vigencia::pluck('vigencia', 'id');
        $registro = sistema_parametros_tramites::find($id);
        return view('admin.sistema.parametros.tramites.editarRegistro', ['vigencias'=>$vigencias, 'registro'=>$registro])->render();
    }

    public function tramites_guardarCambios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registro_id' => 'required|integer|exists:mysql_system.parametros_tramites,id',
            'vigencia' => ['required','integer','exists:mysql_system.vigencia,id',Rule::unique('mysql_system.parametros_tramites','vigencia_id')->ignore($request->registro_id)],
            'radicado_tramite' => 'required|numeric',
            'logo_turno' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'inicio_atencion_submit' => 'required|date_format:H:i',
            'fin_atencion_submit' => 'required|date_format:H:i',
            'habilita_turno_rellamado' => 'required|in:SI,NO',
            'habilita_turno_preferencial' => 'required|in:SI,NO',
            'habilitar_turno_transferencia' => 'required|in:SI,NO',
            'tiempo_espera_turno' => 'required|integer'
        ], [
            'registro_id.required' => 'No se ha especificado el registro a modificar.',
            'registro_id.integer' => 'El ID del registro especificado no tiene un formato válido.',
            'registro_id.exists' => 'El registro especificado no existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificado no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en la base de datos.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'radicado_tramite.required' => 'No se ha especificado el consecutivo inicial de los radicados de entrada.',
            'radicado_tramite.numeric' => 'El consecutivo inicial de los radicados de entrada no tiene un formato válido. Debe ser numérico.',
            'logo_turno.mimes' => 'El logo proporcionado para la etiqueta de turno no tiene un formato válido.',
            'logo_turno.mimetypes' => 'El logo proporcionado para la etiqueta de turno no tiene un formato válido.',
            'logo_turno.max' => 'El tamaño del logo proporcionado para la etiqueta de turno excede el máximo permitido de 2MB.',
            'inicio_atencion_submit.required' => 'No se ha especificado la hora de inicio de atención al público.',
            'inicio_atencion_submit.date_format' => 'La hora especificado de inicio de atención al público no tiene un formato válido.',
            'fin_atencion_submit.required' => 'No se ha especificado la hora de fin de atención al público.',
            'fin_atencion_submit.date_format' => 'La hora especificado de fin de atención al público no tiene un formato válido.',
            'habilita_turno_rellamado.in' => 'El valor especificado para habilitar el re-llamado de turnos no es válido.',
            'habilita_turno_rellamado.required' => 'No se ha especificado el valor para habilitar el re-llamado de turnos.',
            'habilita_turno_preferencial.required' => 'No se ha especificado el valor para habilitar turnos preferentes.',
            'habilita_turno_preferencial.in' => 'El valor especificado para habilitar turnos preferentes no es válido.',
            'habilitar_turno_transferencia.required' => 'No se ha especificado el valor para habilitar la transferencia de turnos.',
            'habilitar_turno_transferencia.in' => 'El valor especificado para habilitar la transferencia de turnos no es válido.',
            'tiempo_espera_turno.required' => 'No se ha especificado el tiempo de espera del turno después de llamado.',
            'tiempo_espera_turno.integer' => 'El valor especificado para el tiempo de espera del turno después de llamado no tiene un formato válido.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            try{
                $registro = sistema_parametros_tramites::find($request->registro_id);
                $registro->radicado_tramite_consecutivo = $request->radicado_tramite;
                $registro->inicio_atencion = $request->inicio_atencion_submit;
                $registro->fin_atencion = $request->fin_atencion_submit;
                $registro->turno_rellamado = $request->habilita_turno_rellamado;
                $registro->turno_preferencial = $request->habilita_turno_preferencial;
                $registro->turno_transferencia = $request->habilitar_turno_transferencia;
                $registro->turno_tiempo_espera = $request->tiempo_espera_turno;
                $registro->vigencia_id = $request->vigencia;

                if($request->logo_turno != null){
                    $registro->turno_logo = $registro->id.'_logo_turno.jpg';
                    \Storage::disk('parametros')->putFileAs('tramites', $request->logo_turno, $registro->id.'_logo_turno.jpg');
                }

                $registro->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han realizado los cambios correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function tramites_filtrarRegistros(Request $request)
    {
        $registros = null;
        $parametro = $request->parametro;
        switch ($request->criterio)
        {
            case 1:
                $registros = sistema_parametros_tramites::with('hasVigencia')->whereHas('hasVigencia', function ($query) use ($parametro){
                    $query->vigencia = $parametro;
                })->get();
                break;
        }
        return view('admin.sistema.parametros.tramites.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function vigencias_administrar()
    {
        $filtros = [
            '1' => 'Año'
        ];

        $sFiltro = null;

        return view('admin.sistema.parametros.vigencias.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function vigencias_obtenerRegistros()
    {
        $registros = sistema_parametros_vigencia::with('hasEmpresa', 'hasPQR', 'hasTramite')->paginate(30);
        return view('admin.sistema.parametros.vigencias.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function vigencias_nuevoRegistro()
    {
        $vigenciaActual = sistema_parametros_vigencia::orderBy('vigencia', 'desc')->first();
        return view('admin.sistema.parametros.vigencias.nuevoRegistro', ['vigenciaActual'=>$vigenciaActual])->render();
    }

    public function vigencias_crearRegistro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vigencia' => 'required|date_format:Y|unique:mysql_system.vigencia,vigencia',
            'inicio_vigencia_submit' => 'required|date',
            'fin_vigencia_submit' => 'required|date', 
            'vigencia_salario_minimo' => 'required|numeric',           
            'empresa_nombre' => 'string|required',
            'empresa_logo_menu' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'empresa_logo' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'empresa_header' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'empresa_map_coordinates' => 'required|string',
            'empresa_nombre_director' => 'required|string',
            'empresa_firma_director' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'empresa_sigla' => 'string|required',
            'empresa_direccion' => 'string|required',
            'empresa_telefono' => 'string|required',
            'empresa_web' => 'string|required',
            'empresa_correo' => 'email|required',
            'empresa_correo_administrador' => 'email|required',
            'empresa_descripcion' => 'required|string',
            'empresa_horario' => 'required|string',
            'empresa_facebook' => 'required|string',
            'empresa_twitter' => 'required|string',
            'empresa_nombre_inspector' => 'required|string',
            'empresa_firma_inspector' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'gd_radicado_entrada' => 'required|numeric',
            'gd_radicado_salida' => 'required|numeric',
            'gd_sancion_consecutivo' => 'required_numeric',
            'pqr_editar_resuelto' => 'required|in:SI,NO',
            'pqr_previo_aviso' => 'required|integer',
            'pqr_logo_radicado' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'tramite_logo_turno' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'tramite_inicio_atencion_submit' => 'required|date_format:H:i',
            'tramite_fin_atencion_submit' => 'required|date_format:H:i',
            'tramite_habilita_turno_rellamado' => 'required|in:SI,NO',
            'tramite_habilita_turno_preferencial' => 'required|in:SI,NO',
            'tramite_habilitar_turno_transferencia' => 'required|in:SI,NO',
            'tramite_tiempo_espera_turno' => 'required|integer',
            'to_consecutivo_inicial' => 'required|numeric',
            'to_marca_agua' => 'required|string',
            'to_valor_unitario' => 'required|numeric',
            'to_vigencia_dias' => 'required|numeric',
            'to_imagen_encabezado' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000'
        ], [
            'vigencia.required' => 'No se ha especificado el año de la vigencia.',
            'vigencia.date_format' => 'El valor especificado para el año de la vigencia no tiene un formato válido.',
            'vigencia.unique' => 'Ya existe un registro con el año especificado.',
            'inicio_vigencia_submit.required' => 'No se ha especificado la fecha de inicio de la vigencia.',
            'inicio_vigencia_submit.date' => 'El valor especificado para la fecha de inicio de la vigencia no tiene un formato válido.',
            'fin_vigencia_submit.required' => 'No se ha especificado la fecha de terminación de la vigencia.',
            'fin_vigencia_submit.date' => 'El valor especificado para la fecha de terminación de la vigencia no tiene un formato válido.',
            'vigencia_salario_minimo.required' => 'No se ha especificado el salario mínimo.',    
            'vigencia_salario_minimo.numeric' => 'El valor especificado para el salario mínimo no es válido.',    
            'empresa_nombre.string' => 'El nombre especificado no tiene un formato válido.',
            'empresa_nombre.required' => 'No se ha especificado el nombre de la empresa.',
            'empresa_logo_menu.required' => 'No se ha especificado el logo para el menú.',
            'empresa_logo_menu.mimes' => 'El logo de menú especificado no tiene un formato válido.',
            'empresa_logo_menu.mimetypes' => 'El logo de menú especificado no tiene un formato válido.',
            'empresa_logo_menu.max' => 'El tamaño del logo de menú especificado excede el máximo permitido de 2MB.',
            'empresa_logo.required' => 'No se ha especificado el logo de la empresa.',
            'empresa_logo.mimes' => 'El logo de la empresa especificado no tiene un formato válido.',
            'empresa_logo.mimetypes' => 'El logo de la empresa especificado no tiene un formato válido.',
            'empresa_logo.max' => 'El tamaño del logo de la empresa especificado excede el máximo permitido de 2MB.',
            'empresa_header.required' => 'No se ha especificado la imagen de encabezado para las páginas públicas.',
            'empresa_header.mimes' => 'La imagen de encabezado para las páginas públicas especificada no tiene un formato válido.',
            'empresa_header.mimetypes' => 'La imagen de encabezado para las páginas públicas especificada no tiene un formato válido.',
            'empresa_header.max' => 'El tamaño de la imagen de encabezado para las páginas públicas especificada excede el máximo permitido de 2MB;',
            'empresa_map_coordinates.required' => 'No se ha especificado las coordenadas de ubicación para el mapa de google maps.',
            'empresa_map_coordinates.string' => 'Las coordenadas especificadas no tienen un formato válido.',
            'empresa_nombre_director.required' => 'No se ha especificado el nombre del director.',
            'empresa_nombre_director.string' => 'El nombre del director no tiene un formato válido.',
            'empresa_firma_director.mimes' => 'La firma especificada no tiene un formato válido.',
            'empresa_firma_director.mimetypes' => 'La firma especificada no tiene un formato válido.',
            'empresa_firma_director.max' => 'El tamaño de la firma excede el máximo permitido de 2MB.',
            'empresa_firma_director.required' => 'No se ha especificado la firma del director.',
            'empresa_sigla.string' => 'La sigla del nombre de la empresa especificada no tiene un formato válido.',
            'empresa_sigla.required' => 'No se ha especificado la sigla del nombre de la empresa.',
            'empresa_direccion.string' => 'La dirección de la empresa especificada no tiene un formato válido.',
            'empresa_direccion.required' => 'No se ha especificado la dirección de la empresa.',
            'empresa_telefono.string' => 'El teléfono de la empresa especificado no tiene un formato válido.',
            'empresa_telefono.required' => 'No se ha especificado el teléfono de la empresa.',
            'empresa_web.string' => 'La página web de la empresa especificada no tiene un formato válido.',
            'empresa_web.required' => 'No se ha especificado la página web de la empresa.',
            'empresa_correo.email' => 'El correo de contacto de la empresa especificado no tiene un formato válido.',
            'empresa_correo.required' => 'No se ha especificado el correo de contacto de la empresa.',
            'empresa_correo_administrador.email' => 'El correo del administrador especificado no tiene un formato válido.',
            'empresa_correo_administrador.required' => 'No se ha especificado el correo del administrador.',
            'empresa_descripcion.required' => 'No se ha especificado la descripción de la empresa.',
            'empresa_descripcion.string' => 'La descripción de la empresa especificada no tiene un formato válido.',
            'empresa_horario.required' => 'No se ha especificado el horario.',
            'empresa_horario.string' => 'El horario especificado no tiene un formato válido.',
            'empresa_facebook.required' => 'No se ha especificado el username en Facebook de la empresa.',
            'empresa_facebook.string' => 'El username de Facebook especificado no tiene un formato válido.',
            'empresa_twitter.required' => 'No se ha especificado el username en Twitter de la empresa.',
            'empresa_twitter.string' => 'El username de Twitter especificado no tiene un formato válido.',
            'empresa_nombre_inspector.required' => 'No se ha especificado el nombre del director.',
            'empresa_nombre_inspector.string' => 'El nombre del director no tiene un formato válido.',
            'empresa_firma_inspector.mimes' => 'La firma especificada no tiene un formato válido.',
            'empresa_firma_inspector.mimetypes' => 'La firma especificada no tiene un formato válido.',
            'empresa_firma_inspector.max' => 'El tamaño de la firma excede el máximo permitido de 2MB.',
            'empresa_firma_inspector.required' => 'No se ha especificado la firma del director.',
            'gd_radicado_entrada.required' => 'No se ha especificado el consecutivo inicial de los radicados de entrada.',
            'gd_radicado_entrada.numeric' => 'El consecutivo inicial de los radicados de entrada no tiene un formato válido. Debe ser numérico.',
            'gd_radicado_salida.required' => 'No se ha especificado el consecutivo inicial de los radicados desalida.',
            'gd_radicado_salida.numeric' => 'El consecutivo inicial de los radicados de salida no tiene un formato válido. Debe ser numérico.',
            'pqr_editar_resuelto.required' => 'No se ha especificado si se permite editar los procesos PQR ya resueltos.',
            'pqr_editar_resuelto.in' => 'El valor especificado para editar procesos PQR ya resueltos no es válido. Debe ser SI o NO.',
            'pqr_previo_aviso.required' => 'No se ha especificado de cada cuantos días se comprobará el estado de los procesos PQr para dar previo aviso.',
            'pqr_previo_aviso.integer' => 'El valor especificado de cada cuantos días se comprobará el estado de los procesos PQr para dar previo aviso no tiene un formato válido.',
            'pqr_logo_radicado.required' => 'No se ha proporcionado el logo para la etiqueta de radicado.',
            'pqr_logo_radicado.mimes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'pqr_logo_radicado.mimetypes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'pqr_logo_radicado.max' => 'El tamaño del logo proporcionado para la etiqueta de radicado excede el máximo permitido de 2MB.',
            'tramite_logo_turno.required' => 'No se ha proporcionado el logo para la etiqueta de turno.',
            'tramite_logo_turno.mimes' => 'El logo proporcionado para la etiqueta de turno no tiene un formato válido.',
            'tramite_logo_turno.mimetypes' => 'El logo proporcionado para la etiqueta de turno no tiene un formato válido.',
            'tramite_logo_turno.max' => 'El tamaño del logo proporcionado para la etiqueta de turno excede el máximo permitido de 2MB.',
            'tramite_inicio_atencion_submit.required' => 'No se ha especificado la hora de inicio de atención al público.',
            'tramite_inicio_atencion_submit.date_format' => 'La hora especificado de inicio de atención al público no tiene un formato válido.',
            'tramite_fin_atencion_submit.required' => 'No se ha especificado la hora de fin de atención al público.',
            'tramite_fin_atencion_submit.date_format' => 'La hora especificado de fin de atención al público no tiene un formato válido.',
            'tramite_habilita_turno_rellamado.in' => 'El valor especificado para habilitar el re-llamado de turnos no es válido.',
            'tramite_habilita_turno_rellamado.required' => 'No se ha especificado el valor para habilitar el re-llamado de turnos.',
            'tramite_habilita_turno_preferencial.required' => 'No se ha especificado el valor para habilitar turnos preferentes.',
            'tramite_habilita_turno_preferencial.in' => 'El valor especificado para habilitar turnos preferentes no es válido.',
            'tramite_habilitar_turno_transferencia.required' => 'No se ha especificado el valor para habilitar la transferencia de turnos.',
            'tramite_habilitar_turno_transferencia.in' => 'El valor especificado para habilitar la transferencia de turnos no es válido.',
            'tramite_tiempo_espera_turno.required' => 'No se ha especificado el tiempo de espera del turno después de llamado.',
            'tramite_tiempo_espera_turno.integer' => 'El valor especificado para el tiempo de espera del turno después de llamado no tiene un formato válido.',
            'to_consecutivo_inicial.required' => 'No se ha especificado el consecutivo inicial.',
            'to_consecutivo_inicial.numeric' => 'El consecutivo inicial no tiene un formato válido.',
            'to_marca_agua.required' => 'No se ha especificado la marca de agua.',
            'to_marca_agua.string' => 'La marca de agua especificada no tiene un formato válido.',
            'to_valor_unitario.required' => 'No se ha especificado el valor unitario.',
            'to_valor_unitario.numeric' => 'El valor unitario especificado no tiene un formato válido.',
            'to_vigencia_dias.required' => 'No se ha especificado la vigencia en días.',
            'to_vigencia_dias.numeric' => 'El valor especificado para la vigencia en días no tiene un formato válido.',
            'to_imagen_encabezado.mimes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'to_imagen_encabezado.mimetypes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'to_imagen_encabezado.max' => 'El tamaño del logo proporcionado para la etiqueta de radicado excede el máximo permitido de 2MB.'
        ]);

        if ($validator->fails()) {
            $request->flash();
            $vigenciaActual = sistema_parametros_vigencia::orderBy('vigencia', 'desc')->first();
            return view('admin.sistema.parametros.vigencias.nuevoRegistro', ['vigenciaActual'=>$vigenciaActual])->withErrors($validator->errors()->all())->render();
        }else{
            $success = false;
            $nuevaVigencia = null;
            $vigenciaActual = null;
            try{
                /*
                 * Se impide crear o modificar registros en el sistema para evitar que se pierdan datos en el sistema.
                 */
                Setting::set('db_autorizado', false);
                Setting::save();
                /*
                 * Realizamos copia de seguridad a las bases de datos
                 */
                //$backup = \Artisan::call('backup:run --only-db');
                /*
                 * Comprobacion de copia de seguridad
                 */
                /*if($backup === 0){
                    $request->flash();
                    $vigenciaActual = sistema_parametros_vigencia::orderBy('vigencia', 'desc')->first();
                    return view('admin.sistema.parametros.vigencias.nuevoRegistro', ['vigenciaActual'=>$vigenciaActual])->withErrors(['Ha ocurrido un error en la realización de la copia de seguridad.'])->render();
                }*/
                /*
                 * Se obtiene la última vigencia
                 */
                $ultimaVigencia = sistema_parametros_vigencia::orderBy('vigencia', 'desc')->first();
                /*
                 * Iniciamos la transacción
                 */
                \DB::beginTransaction();
                /*
                 * Se crea la nueva vigencia
                 */
                $nuevaVigencia = sistema_parametros_vigencia::create([
                    'vigencia' => $ultimaVigencia->vigencia + 1,
                    'impedir_cambios' => 'SI',
                    'inicio_vigencia' => $request->inicio_vigencia_submit,
                    'final_vigencia' => $request->fin_vigencia_submit,
                    'created_at' => date('Y-m-d H:i:s'),
                    'salario_minimo' => $request->vigencia_salario_minimo,
                ]);
                /*
                 * Se crea el registro de la empresa
                 */
                $empresa = sistema_parametros_empresa::create([
                    'vigencia_id' => $nuevaVigencia->id,
                    'empresa_nombre' => strtoupper($request->empresa_nombre),
                    'empresa_map_coordinates' => $request->empresa_map_coordinates,
                    'nombre_director' => strtoupper($request->empresa_nombre_director),
                    'empresa_sigla' => strtoupper($request->empresa_sigla),
                    'empresa_direccion' => $request->empresa_direccion,
                    'empresa_telefono' => $request->empresa_telefono,
                    'empresa_web' => $request->empresa_web,
                    'empresa_correo_contacto' => $request->empresa_correo,
                    'correo_administrador' => $request->empresa_correo_administrador,
                    'descripcion' => $request->empresa_descripcion,
                    'horario' => $request->empresa_horario,
                    'keywords' => $request->empresa_keywords,
                    'nombre_inspector' => $request->empresa_nombre_inspector,
                    'facebook' => $request->empresa_facebook,
                    'twitter' => $request->empresa_twitter,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $empresa->empresa_logo_menu = $empresa->id.'_logo_menu.jpg';
                $empresa->empresa_logo = $empresa->id.'_logo.jpg';
                $empresa->empresa_header = $empresa->id.'_header.jpg';
                $empresa->firma_director = $empresa->id.'_firma_director.jpg';
                $empresa->firma_inspector = $empresa->id.'_firma_inspector.jpg';
                $empresa->save();

                \Storage::disk('parametros')->putFileAs('empresa', $request->empresa_logo_menu, $empresa->id.'_logo_menu.jpg');
                \Storage::disk('parametros')->putFileAs('empresa', $request->empresa_logo, $empresa->id.'_logo.jpg');
                \Storage::disk('parametros')->putFileAs('empresa', $request->empresa_header, $empresa->id.'_header.jpg');
                \Storage::disk('local')->putFileAs('otros/empresa', $request->empresa_firma_director, $empresa->id.'_firma_director.jpg');
                \Storage::disk('local')->putFileAs('otros/empresa', $request->empresa_firma_inspector, $empresa->id.'_firma_director.jpg');

                chmod(storage_path('app/otros/empresa/' . $empresa->firma_director), 0640);
                chmod(storage_path('app/otros/empresa/' . $empresa->firma_inspector), 0640);

                /*
                 * Se crea el registro de Gestion Documental
                 */
                $gd = sistema_parametros_gd::create([
                    'radicado_entrada_consecutivo' => $request->gd_radicado_entrada,
                    'radicado_salida_consecutivo' => $request->gd_radicado_salida,
                    'sancion_consecutivo' => $request->sancion_consecutivo,
                    'vigencia_id' => $nuevaVigencia->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                /*
                 * Se crea el registro de PQR
                 */
                $pqr = sistema_parametros_pqr::create([
                    'editar_pqr_resuelto' => $request->pqr_editar_resuelto,
                    'dias_previo_aviso' => $request->pqr_previo_aviso,
                    'vigencia_id' => $nuevaVigencia->id,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                $pqr->logo_pqr_radicado = $pqr->id.'_logo_radicado.jpg';
                $pqr->save();

                \Storage::disk('parametros')->putFileAs('pqr', $request->pqr_logo_radicado, $pqr->id.'_logo_radicado.jpg');

                /*
                 * Se crea el registro de Tramites
                 */
                $tramite = sistema_parametros_tramites::create([
                    'inicio_atencion' => $request->tramite_inicio_atencion_submit,
                    'fin_atencion' => $request->tramite_fin_atencion_submit,
                    'turno_rellamado' => $request->tramite_habilita_turno_rellamado,
                    'turno_preferencial' => $request->tramite_habilita_turno_preferencial,
                    'turno_transferencia' => $request->tramite_habilitar_turno_transferencia,
                    'vigencia_id' => $nuevaVigencia->id,
                    'turno_tiempo_espera' => $request->tramite_tiempo_espera_turno,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);

                $tramite->turno_logo = $tramite->id.'_logo_turno.jpg';
                $tramite->save();

                \Storage::disk('parametros')->putFileAs('tramites', $request->tramite_logo_turno, $tramite->id.'_logo_turno.jpg');

                $to = new sistema_parametros_to();
                $to->consecutivo_inicial = $request->to_consecutivo_inicial;
                $to->marca_agua = $request->to_marca_agua;
                $to->valor_unitario = $request->to_valor_unitario;
                $to->vigencia_id = $nuevaVigencia->id;
                $to->save();              
                //7-Completamos la transacción
                \DB::commit();
                $success = true;
            }catch (\Exception $e){
                /*
                 * Deshaciendo los cambios por error en el proceso
                 */
                \DB::rollBack();
            }
            if($success == true){
                /*
                 * 11-Finalizamos
                 */
                Setting::set('vigencia',$nuevaVigencia->vigencia);
                Setting::save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha creado el registro en el sistema. Por favor recarga la página para que surtan los efectos.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }else{
                /*
                 * Mensaje de error
                 */
                $request->flash();
                $vigenciaActual = sistema_parametros_vigencia::orderBy('vigencia', 'desc')->first();
                return view('admin.sistema.parametros.vigencias.nuevoRegistro', ['vigenciaActual'=>$vigenciaActual])->withErrors(['Por favor intente nuevamente y si el problema persiste contacte a un administrador.'])->render();
            }
        }
    }

    public function vigencias_editarRegistro($id)
    {
        $registro = sistema_parametros_vigencia::find($id);
        return view('admin.sistema.parametros.vigencias.editarRegistro', ['registro'=>$registro])->render();
    }

    public function vigencias_guardarCambios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registro_id' => 'required|integer|exists:mysql_system.vigencia,id',
            'anio' => ['required','date_format:Y',Rule::unique('mysql_system.vigencia','vigencia')->ignore($request->registro_id)],
            'inicio_vigencia_submit' => 'required|date',
            'fin_vigencia_submit' => 'required|date',
            'vigencia_salario_minimo' => 'required|numeric'
        ], [
            'registro_id.required' => 'No se ha especificado el registro a modificar.',
            'registro_id.integer' => 'El ID del registro especificado no tiene un formato válido.',
            'registro_id.exists' => 'El registro especificado no existe en el sistema.',
            'anio.required' => 'No se ha especificado el año de la vigencia.',
            'anio.date_format' => 'El valor especificado para el año de la vigencia no tiene un formato válido.',
            'anio.unique' => 'Ya existe un registro con el año especificado.',
            'inicio_vigencia_submit.required' => 'No se ha especificado la fecha de inicio de la vigencia.',
            'inicio_vigencia_submit.date' => 'El valor especificado para la fecha de inicio de la vigencia no tiene un formato válido.',
            'fin_vigencia_submit.required' => 'No se ha especificado la fecha de terminación de la vigencia.',
            'fin_vigencia_submit.date' => 'El valor especificado para la fecha de terminación de la vigencia no tiene un formato válido.',
            'vigencia_salario_minimo.required' => 'No se ha especificado el salario mínimo.',    
            'vigencia_salario_minimo.numeric' => 'El valor especificado para el salario mínimo no es válido.',  
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            try{
                $registro = sistema_parametros_vigencia::find($request->registro_id);
                $registro->inicio_vigencia = $request->inicio_vigencia_submit;
                $registro->final_vigencia = $request->fin_vigencia_submit;
                $registro->salario_minimo = $request->vigencia_salario_minimo;
                $registro->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han realizado los cambios correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                echo $e->getMessage();
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function vigencias_filtrarRegistros(Request $request)
    {
        $registros = null;
        $parametro = $request->parametro;
        switch ($request->criterio)
        {
            case 1:
                $registros = sistema_parametros_vigencia::with('hasEmpresa', 'hasPQR', 'hasTramite')->where('vigencia', $parametro)->get();
                break;
        }
        return view('admin.sistema.parametros.vigencias.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function gd_administrar()
    {
        $filtros = [
            '1' => 'Vigencia'
        ];

        $sFiltro = null;

        return view('admin.sistema.parametros.gestion_documental.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function gd_obtenerRegistros()
    {
        $registros = sistema_parametros_gd::paginate(30);

        return view('admin.sistema.parametros.gestion_documental.listadoRegistros', ['registros' => $registros])->render();
    }

    public function gd_nuevoRegistro()
    {
        $vigencias = sistema_parametros_vigencia::doesntHave('hasGD')->pluck('vigencia','id');
        if($vigencias->count() <= 0){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Todas las vigencias ya tienen un registro de Gestión Documental. Deberá modificar el registro existente.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
        return view('admin.sistema.parametros.gestion_documental.nuevoRegistro', ['vigencias'=>$vigencias])->render();
    }

    public function gd_crearRegistro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vigencia' => ['required', 'integer', 'exists:mysql_system.vigencia,id', Rule::unique('mysql_system.parametros_gestion_documental', 'vigencia_id')->ignore($request->registro_id)],
            'radicado_entrada' => 'required|numeric',
            'radicado_salida' => 'required|numeric',
            'sancion_consecutivo' => 'required|numeric',
            'encabezado_documento' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png',
            'pie_documento' => 'required|mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png',
        ], [
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificado no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en la base de datos.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'radicado_entrada.required' => 'No se ha especificado el consecutivo inicial de los radicados de entrada.',
            'radicado_entrada.numeric' => 'El consecutivo inicial de los radicados de entrada no tiene un formato válido. Debe ser numérico.',
            'radicado_salida.required' => 'No se ha especificado el consecutivo inicial de los radicados desalida.',
            'radicado_salida.numeric' => 'El consecutivo inicial de los radicados de salida no tiene un formato válido. Debe ser numérico.',
            'encabezado_documento.required' => 'No se ha proporcionado la imágen para el encabezado de los documentos.',
            'encabezado_documento.mimes' => 'La imágen proporcionada para el encabezado de los documentos no tiene un formato válido.',
            'encabezado_documento.mimetypes' => 'La imágen proporcionada para el encabezado de los documentos no tiene un formato válido.',
            'pie_documento.required' => 'No se ha proporcionado la imágen para el pie de página de los documentos.',
            'pie_documento.mimes' => 'La imágen proporcionada para el pie de página de los documentos no tiene un formato válido.',
            'pie_documento.mimetypes' => 'La imágen proporcionada para el pie de página de los documentos no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                $registro = new sistema_parametros_gd();
                $registro->radicado_entrada_consecutivo = $request->radicado_entrada;
                $registro->radicado_salida_consecutivo = $request->radicado_salida;
                $registro->sancion_consecutivo = $request->sancion_consecutivo;
                $registro->save();

                \Storage::disk('parametros')->putFileAs('gd', $request->encabezado_documento, $registro->id.'_encabezado_documento.jpg');
                \Storage::disk('parametros')->putFileAs('gd', $request->pie_documento, $registro->id.'_pie_documento.jpg');

                $registro->encabezado_documento = $registro->id.'_encabezado_documento.jpg';
                $registro->pie_documento = $registro->id.'_pie_documento.jpg';
                $registro->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se ha registrado la información de Gestión Documental.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function gd_editarRegistro($id)
    {
        $vigencias = sistema_parametros_vigencia::pluck('vigencia', 'id');
        $registro = sistema_parametros_gd::find($id);
        return view('admin.sistema.parametros.gestion_documental.editarRegistro', ['vigencias' => $vigencias, 'registro' => $registro])->render();
    }

    public function gd_guardarCambios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registro_id' => 'required|integer|exists:mysql_system.parametros_gestion_documental,id',
            'vigencia' => ['required', 'integer', 'exists:mysql_system.vigencia,id', Rule::unique('mysql_system.parametros_gestion_documental', 'vigencia_id')->ignore($request->registro_id)],
            'radicado_entrada' => 'required|numeric',
            'radicado_salida' => 'required|numeric',
            'sancion_consecutivo' => 'required|numeric',
            'encabezado_documento' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
            'pie_documento' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000',
        ], [
            'registro_id.required' => 'No se ha especificado el registro a modificar.',
            'registro_id.integer' => 'El ID del registro especificado no tiene un formato válido.',
            'registro_id.exists' => 'El registro especificado no existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificado no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en la base de datos.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'radicado_entrada.required' => 'No se ha especificado el consecutivo inicial de los radicados de entrada.',
            'radicado_entrada.numeric' => 'El consecutivo inicial de los radicados de entrada no tiene un formato válido. Debe ser numérico.',
            'radicado_salida.required' => 'No se ha especificado el consecutivo inicial de los radicados desalida.',
            'radicado_salida.numeric' => 'El consecutivo inicial de los radicados de salida no tiene un formato válido. Debe ser numérico.',
            'encabezado_documento.mimes' => 'La imágen proporcionada para el encabezado de los documentos no tiene un formato válido.',
            'encabezado_documento.mimetypes' => 'La imágen proporcionada para el encabezado de los documentos no tiene un formato válido.',
            'pie_documento.mimes' => 'La imágen proporcionada para el pie de página de los documentos no tiene un formato válido.',
            'pie_documento.mimetypes' => 'La imágen proporcionada para el pie de página de los documentos no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        } else {
            try {
                $registro = sistema_parametros_gd::find($request->registro_id);
                $registro->radicado_entrada_consecutivo = $request->radicado_entrada;
                $registro->radicado_salida_consecutivo = $request->radicado_salida;
                $registro->sancion_consecutivo = $request->sancion_consecutivo;
                $registro->save();

                \Storage::disk('parametros')->putFileAs('gd', $request->encabezado_documento, $registro->id.'_encabezado_documento.jpg');
                \Storage::disk('parametros')->putFileAs('gd', $request->pie_documento, $registro->id.'_pie_documento.jpg');

                $registro->encabezado_documento = $registro->id.'_encabezado_documento.jpg';
                $registro->pie_documento = $registro->id.'_pie_documento.jpg';
                $registro->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han realizado los cambios correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            } catch (\Exception $e) {
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function gd_filtrarRegistros(Request $request)
    {
        $registros = null;
        $parametro = $request->parametro;
        switch ($request->criterio) {
            case 1:
                $registros = sistema_parametros_gd::with('hasVigencia')->whereHas('hasVigencia', function ($query) use ($parametro) {
                    $query->vigencia = $parametro;
                })->get();
                break;
        }
        return view('admin.sistema.parametros.gestion_documental.listadoRegistros', ['registros' => $registros])->render();
    }

    public function to_administrar()
    {
        $filtros = [
            '1' => 'Vigencia'
        ];

        $sFiltro = null;

        return view('admin.sistema.parametros.to.administrar', ['filtros' => $filtros, 'sFiltro' => $sFiltro]);
    }

    public function to_obtenerRegistros()
    {
        $registros = sistema_parametros_to::paginate(30);

        return view('admin.sistema.parametros.to.listadoRegistros', ['registros'=>$registros])->render();
    }

    public function to_nuevoRegistro()
    {
        $vigencias = sistema_parametros_vigencia::doesntHave('hasTO')->pluck('vigencia','id');
        if($vigencias->count() <= 0){
            return response()->view('admin.mensajes.errors', [
                'errors' => ['Todas las vigencias ya tienen un registro de Tarjeta de Operación. Deberá modificar el registro existente.'],
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }
        return view('admin.sistema.parametros.to.nuevoRegistro', ['vigencias'=>$vigencias])->render();
    }

    public function to_crearRegistro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vigencia' => ['required','integer','exists:mysql_system.vigencia,id',Rule::unique('mysql_system.parametros_to','vigencia_id')->ignore($request->registro_id)],
            'consecutivo_inicial' => 'required|numeric',
            'marca_agua' => 'required|string',
            'valor_unitario' => 'required|numeric',
            'imagen_encabezado' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000'
        ], [
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificado no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en la base de datos.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'consecutivo_inicial.required' => 'No se ha especificado el consecutivo inicial.',
            'consecutivo_inicial.numeric' => 'El consecutivo inicial no tiene un formato válido.',
            'marca_agua.required' => 'No se ha especificado la marca de agua.',
            'marca_agua.string' => 'La marca de agua especificada no tiene un formato válido.',
            'valor_unitario.required' => 'No se ha especificado el valor unitario.',
            'valor_unitario.numeric' => 'El valor unitario especificado no tiene un formato válido.',
            'imagen_encabezado.mimes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'imagen_encabezado.mimetypes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'imagen_encabezado.max' => 'El tamaño del logo proporcionado para la etiqueta de radicado excede el máximo permitido de 2MB.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            try{
                $registro = new sistema_parametros_to();
                $registro->consecutivo_inicial = $request->consecutivo_inicial;
                $registro->marca_agua = $request->marca_agua;
                $registro->valor_unitario = $request->valor_unitario;
                $registro->vigencia_id = $request->vigencia;
                $registro->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han realizado los cambios correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function to_editarRegistro($id)
    {
        $vigencias = sistema_parametros_vigencia::pluck('vigencia', 'id');
        $registro = sistema_parametros_to::find($id);
        return view('admin.sistema.parametros.to.editarRegistro', ['vigencias'=>$vigencias, 'registro'=>$registro])->render();
    }

    public function to_guardarCambios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'registro_id' => 'required|integer|exists:mysql_system.parametros_pqr,id',
            'vigencia' => ['required','integer','exists:mysql_system.vigencia,id',Rule::unique('mysql_system.parametros_to','vigencia_id')->ignore($request->registro_id)],
            'consecutivo_inicial' => 'required|numeric',
            'marca_agua' => 'required|string',
            'valor_unitario' => 'required|numeric',
            'vigencia_dias' => 'required|numeric',
            'imagen_encabezado' => 'mimes:jpeg,jpg,png|mimetypes:image/jpeg,image/jpg,image/png|max:2000'
        ], [
            'registro_id.required' => 'No se ha especificado el registro a modificar.',
            'registro_id.integer' => 'El ID del registro especificado no tiene un formato válido.',
            'registro_id.exists' => 'El registro especificado no existe en el sistema.',
            'vigencia.required' => 'No se ha especificado la vigencia.',
            'vigencia.integer' => 'El ID de la vigencia especificado no tiene un formato válido.',
            'vigencia.exists' => 'La vigencia especificada no existe en la base de datos.',
            'vigencia.unique' => 'Ya existe un registro con la vigencia especificada.',
            'consecutivo_inicial.required' => 'No se ha especificado el consecutivo inicial.',
            'consecutivo_inicial.numeric' => 'El consecutivo inicial no tiene un formato válido.',
            'marca_agua.required' => 'No se ha especificado la marca de agua.',
            'marca_agua.string' => 'La marca de agua especificada no tiene un formato válido.',
            'valor_unitario.required' => 'No se ha especificado el valor unitario.',
            'valor_unitario.numeric' => 'El valor unitario especificado no tiene un formato válido.',
            'vigencia_dias.required' => 'No se ha especificado la vigencia en días.',
            'vigencia_dias.numeric' => 'El valor especificado para la vigencia en días no tiene un formato válido.',
            'imagen_encabezado.mimes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'imagen_encabezado.mimetypes' => 'El logo proporcionado para la etiqueta de radicado no tiene un formato válido.',
            'imagen_encabezado.max' => 'El tamaño del logo proporcionado para la etiqueta de radicado excede el máximo permitido de 2MB.'
        ]);

        if ($validator->fails()) {
            return response()->view('admin.mensajes.errors', [
                'errors' => $validator->errors()->all(),
                'encabezado' => 'Errores en la validación:',
            ], 200);
        }else{
            try{
                $registro = sistema_parametros_to::find($request->registro_id);
                $registro->consecutivo_inicial = $request->consecutivo_inicial;
                $registro->marca_agua = $request->marca_agua;
                $registro->valor_unitario = $request->valor_unitario;
                $registro->vigencia_id = $request->vigencia;
                $registro->save();

                return response()->view('admin.mensajes.success', [
                    'mensaje' => 'Se han realizado los cambios correctamente.',
                    'encabezado' => '¡Completado!',
                ], 200);
            }catch (\Exception $e){
                return response()->view('admin.mensajes.errors', [
                    'errors' => ['Por favor intente nuevamente y si el problema persiste, por favor contacte a un administrador.'],
                    'encabezado' => 'Error en el proceso',
                ], 200);
            }
        }
    }

    public function to_filtrarRegistros(Request $request)
    {
        $registros = null;
        $parametro = $request->parametro;
        switch ($request->criterio)
        {
            case 1:
                $registros = sistema_parametros_to::with('hasVigencia')->whereHas('hasVigencia', function ($query) use ($parametro){
                    $query->vigencia = $parametro;
                })->get();
                break;
        }
        return view('admin.sistema.parametros.to.listadoRegistros', ['registros'=>$registros])->render();
    }
}