<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

if ('production' === App::environment()) {
    Artisan::command('migrate:fresh', function () {
        $this->comment('You are not allowed to do this in production!');
    })->describe('Override default command in production.');

    Artisan::command('migrate:refresh', function () {
        $this->comment('You are not allowed to do this in production!');
    })->describe('Override default command in production.');

    Artisan::command('db:seed', function () {
        $this->comment('You are not allowed to do this in production!');
    })->describe('Override default command in production.');
}
