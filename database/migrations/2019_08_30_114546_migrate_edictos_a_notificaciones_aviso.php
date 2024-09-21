<?php

use App\CoactivoComparendo;
use App\CoactivoFotoMultas;
use App\notificacion_aviso;
use App\notificacion_aviso_tipo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MigrateEdictosANotificacionesAviso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notificacion_aviso', function (Blueprint $table) {
            $table->date('fecha_desfijacion')->nullable()->change();
        });

        $tipoAviso = notificacion_aviso_tipo::firstOrCreate([
            'name' => 'Comparendo'
        ]);

        foreach(CoactivoComparendo::all()->chunk(500) as $edictos){
            foreach($edictos as $edicto){
                if(Str::contains($edicto->pathArchive, 'https')){
                    $archivo = $edicto->pathArchive;
                }else{
                    $archivo = Storage::disk('edictos')->get($edicto->pathArchive);
                    $archivo = Storage::disk('notificacionesAviso')->putFileAs('/', $archivo, Str::random().'.pdf');
                }                
                notificacion_aviso::create([
                    'fecha_publicacion' => $edicto->publication_date,
                    'fecha_desfijacion' => null,
                    'numero_documento' => $edicto->cc,
                    'nombre_notificado' => $edicto->name,
                    'documento_notificacion' => $archivo,
                    'numero_proceso' => null,
                    'not_aviso_tipo_id' => $tipoAviso->id
                ]);
                Storage::disk('edictos')->delete($edicto->pathArchive);
                $edicto->delete();
            }            
        }

        $tipoAviso = notificacion_aviso_tipo::firstOrCreate([
            'name' => 'Foto Multa'
        ]);

        foreach(CoactivoFotoMultas::all()->chunk(500) as $edictos){
            if(Str::contains($edicto->pathArchive, 'https')){
                    $archivo = $edicto->pathArchive;
                }else{
                    $archivo = Storage::disk('edictos')->get($edicto->pathArchive);
                    $archivo = Storage::disk('notificacionesAviso')->putFileAs('/', $archivo, Str::random().'.pdf');
                }                
                notificacion_aviso::create([
                    'fecha_publicacion' => $edicto->publication_date,
                    'fecha_desfijacion' => null,
                    'numero_documento' => $edicto->cc,
                    'nombre_notificado' => $edicto->name,
                    'documento_notificacion' => $archivo,
                    'numero_proceso' => null,
                    'not_aviso_tipo_id' => $tipoAviso->id
                ]);
                Storage::disk('edictos')->delete($edicto->pathArchive);
                $edicto->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
