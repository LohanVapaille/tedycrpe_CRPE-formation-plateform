<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Purge quotidienne des conversations inactives (voir config/chat.php)
Schedule::command('chat:purge-inactive')->dailyAt('03:00')->withoutOverlapping();
