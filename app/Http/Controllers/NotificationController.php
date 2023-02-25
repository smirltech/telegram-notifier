<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendLocationRequest;
use App\Http\Requests\SendMessageRequest;
use App\Notifications\TelegramNotification;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use NotificationChannels\Telegram\TelegramLocation;
use NotificationChannels\Telegram\TelegramMessage;

class NotificationController extends Controller
{
    /**
     * @var Repository|Application|mixed
     */
    private string $token;

    public function __construct()
    {
        $this->token = config('services.telegram-bot-api.token');
    }

    /** Send text message to Telegram chat
     * @param SendMessageRequest $request
     * @param string $chatId
     * @return JsonResponse
     */
    public function message(SendMessageRequest $request, string $chatId)
    {
        $content = $request->message['content'];
        $buttons = $request->message['buttons'];
        try {
            $t = TelegramMessage::create()->content($content)->token($request->token ?? $this->token);
            foreach ($buttons as $button) {
                $t->button($button['text'], $button['url']);
            }
            Notification::route('telegram', $chatId)->notify(new TelegramNotification($t));

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
    }

    /** Send location to Telegram chat
     * @param SendLocationRequest $request
     * @param string $chatId
     * @return JsonResponse
     */
    public function location(SendLocationRequest $request, string $chatId)
    {
        $latitude = $request->message['latitude'];
        $longitude = $request->message['longitude'];
        try {
            $t = TelegramLocation::create()->latitude($latitude)->longitude($longitude)->token($request->token ?? $this->token);
            Notification::route('telegram', $chatId)->notify(new TelegramNotification($t));

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
    }
}
