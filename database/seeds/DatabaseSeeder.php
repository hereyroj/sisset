<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(departamentosyciudades::class);
        $this->call(clase_combustible_seed::class);
        $this->call(marca_vehiculo_seed::class);
        $this->call(nivel_servicio_seed::class);
        $this->call(radio_operacion_seed::class);
        $this->call(tipo_carroceria_seed::class);
        $this->call(tipo_vehiculo_seed::class);
        $this->call(rolesypermisos::class);
        $this->call(carpeta_estados::class);
        $this->call(tramites::class);
        $this->call(letras_terminacion::class);
        $this->call(dependencias::class);
        $this->call(tiposDenegaciones::class);
        $this->call(tipo_documento::class);
        $this->call(pqr_tipo_oficio::class);
        $this->call(tipo_pqr_seed::class);
    }
}
