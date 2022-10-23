<?php

use App\Data\Payload;
use App\Notifications\SendGithubPushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

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

Route::match(['get', 'post'], '/github-notify/{id}', function (Request $request, int $id) {
    try {
        //'-868088992' is my telegram chat id
        Notification::route('telegram', $id)->notify(new SendGithubPushNotification(payload: Payload::fromArray(data: $request->all())));
        return Response::json([
            'success' => true,
            'message' => 'Notification sent successfully',
        ]);
    } catch (Exception $e) {
        return Response::json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 400);
    }
});
