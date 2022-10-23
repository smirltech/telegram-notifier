<?php

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

Route::match(['get','post'],'/github-notify/{id}', function (Request $request, int $id) {
    try {
        //'-868088992'
        Notification::route('telegram', $id)->notify(new SendGithubPushNotification($request->all()));
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
