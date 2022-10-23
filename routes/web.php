<?php

use App\Notifications\SendGithubPushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;

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

Route::get('/github-notify-push/{id}', function (Request $request, int $id) {
    try {
        //'-868088992'
        Notification::route('telegram', $id)->notify(new SendGithubPushNotification());
        return Response::json([
            'success' => true,
            'message' => 'Notification sent successfully',
            'data' =>$request->all(),
            'id' => $id
        ]);
    } catch (Exception $e) {
        return Response::json([
            'success' => false,
            'message' => $e->getMessage(),
        ],400);
    }
});
