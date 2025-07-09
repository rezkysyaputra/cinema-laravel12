<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\CancelExpiredPayments;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tambahkan penjadwalan command cancel expired payments
if (!defined('schedule_commands')) {
    define('schedule_commands', function (Schedule $schedule) {
        $schedule->command('payments:cancel-expired')->everyMinute();
    });
}
