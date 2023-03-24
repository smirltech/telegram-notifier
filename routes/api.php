<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;
use NotificationChannels\Telegram\TelegramUpdates;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/telegram-updates', function () {
    $chats = TelegramUpdates::create()->latest()->limit(2)->get();
});

Route::match(['get', 'post'], '/github-notify/{chatId}', [NotificationController::class, 'github']);
Route::match(['get', 'post'], 'github/{chatId}', [NotificationController::class, 'github']);


Route::post('message/{chatId}', [NotificationController::class, 'message']);
Route::post('location/{chatId}', [NotificationController::class, 'location']);
Route::post('poll/{chatId}', [NotificationController::class, 'poll']);
Route::post('contact/{chatId}', [NotificationController::class, 'contact']);
Route::post('file/{chatId}', [NotificationController::class, 'file']);



