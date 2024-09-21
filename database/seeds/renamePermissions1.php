<?php

use Illuminate\Database\Seeder;
use Artesaos\Defender\Permission;

class renamePermissions1 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $oldPermissions = Permission::where('name', 'LIKE', '%liquidacion-vehiculo-%')->get();

        foreach($oldPermissions as $permission){
            $permission->name = str_replace('vehiculo', 'impuesto', $permission->name);
            $permission->save();
        }
    }
}
