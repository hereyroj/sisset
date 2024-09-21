<?php

use Illuminate\Database\Seeder;

class downloadEdictoPdf extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\CoactivoComparendo::chunk(50, function ($edictos){
                foreach ($edictos as $edicto){
                    $url = explode('/', $edicto->pathArchive);
                    if(count($url) > 6) {
                        try {
                            $file = file_get_contents('https://docs.google.com/uc?id=' . $url[5] . '&export=download');
                            \Storage::disk('edictos')->put('comparendos/' . $edicto->id . '.pdf', $file);
                            $edicto->pathArchive = 'comparendos/' . $edicto->id . '.pdf';
                            $edicto->save();
                        }catch (Exception $e){
                            
                        }
                    }else{
                        if(count($url) == 4){
                            try {
                                $url = explode('=', $edicto->pathArchive);
                                $file = file_get_contents('https://docs.google.com/uc?id=' . $url[1] . '&export=download');
                                \Storage::disk('edictos')->put('comparendos/' . $edicto->id . '.pdf', $file);
                                $edicto->pathArchive = 'comparendos/' . $edicto->id . '.pdf';
                                $edicto->save();
                            }catch (Exception $e){
                                
                            }
                        }else{
                            if(count($url) < 2){
                                $edicto->pathArchive = '0000';
                                $edicto->save();
                            }
                        }
                    }
                }
        });

        \App\CoactivoFotoMultas::chunk(50, function ($edictos){
            foreach ($edictos as $edicto){
                $url = explode('/', $edicto->pathArchive);
                if(count($url) > 6) {
                    try {
                        $file = file_get_contents('https://docs.google.com/uc?id=' . $url[5] . '&export=download');
                        \Storage::disk('edictos')->put('fotomultas/' . $edicto->id . '.pdf', $file);
                        $edicto->pathArchive = 'fotomultas/' . $edicto->id . '.pdf';
                        $edicto->save();
                    }catch (Exception $e){
                        
                    }
                }else{
                    if(count($url) == 4){
                        try {
                            $url = explode('=', $edicto->pathArchive);
                            $file = file_get_contents('https://docs.google.com/uc?id=' . $url[1] . '&export=download');
                            \Storage::disk('edictos')->put('fotomultas/' . $edicto->id . '.pdf', $file);
                            $edicto->pathArchive = 'fotomultas/' . $edicto->id . '.pdf';
                            $edicto->save();
                        }catch (Exception $e){
                            
                        }
                    }else{
                        if(count($url) < 2){
                            $edicto->pathArchive = '0000';
                            $edicto->save();
                        }
                    }
                }
            }
        });
    }
}
