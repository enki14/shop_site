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
        //コマンドの登録
        \app\Console\Commands\ItoyokadoCommand::class,
        \app\Console\Commands\SummitCommand::class,
        \app\Console\Commands\Maruetsu_5offCommand::class,
        \app\Console\Commands\Maruetsu_5timesCommand::class,
        \app\Console\Commands\InasanCommand::class,
        \app\Console\Commands\ComodoniCommand::class,
        \app\Console\Commands\Keio3dayCommand::class,
        \app\Console\Commands\TobuTCommand::class,
        \app\Console\Commands\AlpsDonitiCommand::class,
        \app\Console\Commands\AeonWakuCommand::class,
        \app\Console\Commands\SeiyuList_Command::class,
        \app\Console\Commands\DonkiList_Command::class,
        \app\Console\Commands\AeonList_Command::class,
        \app\Console\Commands\yorkList_Command::class,
        \app\Console\Commands\seizyoList_Command::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ItoyokadoCommand')->monthlyOn(28, '11:00');
        $schedule->command('SummitCommand')->weeklyOn(3, '11:00');
        $schedule->command('Maruetsu_5times')->weeklyOn(1, '11:00');
        $schedule->command('Maruetsu_5off')->monthlyOn(23, '11:00');
        $schedule->command('InasanCommand')->monthlyOn(1, '11:00');
        $schedule->command('ComodoniCommand')->weeklyOn(1, '11:00');
        $schedule->command('Keio3day')->weeklyOn(6, '11:00');
        $schedule->command('tobuTmoney')->weeklyOn(1, '11:00');
        $schedule->command('alpsDoniti')->weeklyOn(1, '11:00');
        $schedule->command('aeonwakuwaku')->monthlyOn(26, '11:00');
        $schedule->command('seiyuList')->monthlyOn(1, '11:00');
        $schedule->command('donkiList')->monthlyOn(2, '11:00');
        $schedule->command('aeonList')->monthlyOn(1, '11:00');
        $schedule->command('yorkList')->monthlyOn(2, '11:00');
        $schedule->command('seizyoList')->monthlyOn(1, '11:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
