<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        Commands\HappyBirthday::class,
        Commands\PrevioAvisoPQR::class,
        Commands\ProcesarTurnos::class,
        Commands\TOVencidasUAnuladas::class,
        Commands\ProcesarSolicitudesTramites::class,
        Commands\ProcesarPreAsignaciones::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('send:birthday')->dailyAt('07:15');
        $schedule->command('backup:clean')->monthly()->at('01:00');
        $schedule->command('backup:run --only-files')->weeklyOn(6, '00:00');
        $schedule->command('backup:run --only-db')->daily()->at('00:30');
        $schedule->command('backup:run --only-db')->daily()->at('06:30');
        $schedule->command('backup:run --only-db')->daily()->at('12:30');
        $schedule->command('backup:run --only-db')->daily()->at('18:30');
        $schedule->command('backup:monitor')->daily()->at('03:00');
        $schedule->command('pqr:prealertar')->dailyAt('07:15');
        $schedule->command('turnos:procesar')->dailyAt('06:00');
        $schedule->command('SolicitudesTramites:procesar')->dailyAt('19:00');
        $schedule->command('preasignaciones:procesar')->dailyAt('21:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }

    protected function scheduleTimezone()
    {
        return 'America/Bogota';
    }
}