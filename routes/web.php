<?php

use App\Http\Controllers\ExampleController;
use App\Http\Controllers\HomeController;
use App\Jobs\SendMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('example');
});

Route::get('/publish', [ExampleController::class, 'example']);


Auth::routes();

Route::get('/home', [HomeController::class, 'index'])
    ->name('home');
Route::get('/messages', [HomeController::class, 'messages'])
    ->name('messages');
Route::post('/message', [HomeController::class, 'message'])
    ->name('message');
