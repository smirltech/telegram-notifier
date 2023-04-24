<?php

use App\Notifications\SendGithubPushNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/docs');

Route::match(['get', 'post'], 'git-deploy', function () {
    Artisan::call('git:deploy');
    return 'Deployed successfully';
});
