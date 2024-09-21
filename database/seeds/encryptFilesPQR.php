<?php

use Illuminate\Database\Seeder;

class encryptFilesPQR extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pqrs = \App\gd_pqr::all();
        foreach ($pqrs->chunk(50) as $pqrsChunk){
            foreach ($pqrsChunk as $pqr) {
                if ($pqr->documento_radicado != null) {
                    if (Storage::disk('pqr')->exists($pqr->documento_radicado)) {
                        $file = Storage::disk('pqr')->get($pqr->documento_radicado);
                        $encryptedContent = encrypt($file);
                        Storage::put('dora.dat', $encryptedContent, 'private');
                        Storage::move('dora.dat', 'pqr/' . $pqr->tipo_pqr . '/' . $pqr->id . '/radicado/dora.dat');
                        Storage::disk('pqr')->delete($pqr->documento_radicado);
                        $pqr->documento_radicado = $pqr->tipo_pqr . '/' . $pqr->id . '/radicado/dora.dat';
                        $pqr->save();
                    }
                } elseif ($pqr->pdf != null) {
                    if (Storage::disk('pqr')->exists($pqr->pdf)) {
                        $file = Storage::disk('pqr')->get($pqr->pdf);
                        $encryptedContent = encrypt($file);
                        Storage::put('dora.dat', $encryptedContent, 'private');
                        Storage::move('dora.dat', 'pqr/' . $pqr->tipo_pqr . '/' . $pqr->id . '/radicado/dora.dat');
                        Storage::disk('pqr')->delete($pqr->documento_radicado);
                        $pqr->documento_radicado = $pqr->tipo_pqr . '/' . $pqr->id . '/radicado/dora.dat';
                        $pqr->save();
                    }
                }
            }
        }
    }
}
