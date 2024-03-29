<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use App\Jobs\ProcessXMLFeeds;
use App\Jobs\SendSubscribersNotification;

use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::job(new ProcessXMLFeeds)->twiceDailyAt(10, 14, 19);

Schedule::job(new SendSubscribersNotification)->dailyAt('13:00');