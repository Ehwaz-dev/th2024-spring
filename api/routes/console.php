<?php

use App\Services\EventService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('travel:check', function () {
    /**@var $eventService EventService */
    $eventService = app(EventService::class);
    $eventService->doneExpired();
})->daily();
