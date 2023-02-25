<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendContactRequest;
use App\Http\Requests\SendLocationRequest;
use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\SendPollRequest;
use App\Notifications\TelegramNotification;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use NotificationChannels\Telegram\TelegramContact;
use NotificationChannels\Telegram\TelegramLocation;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramPoll;

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
        try {
            $payload = (object)$request->validated();
            $t = TelegramMessage::create()
                ->content($payload->content)
                ->token($payload->token ?? $this->token);
            foreach ($payload->buttons ?? [] as $button) {
                $t->button($button['text'], $button['url']);
            }
            Notification::route('telegram', $chatId)->notify(new TelegramNotification($t));

            return $this->successResponse();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    private function successResponse()
    {
        return Response::json([
            'success' => true,
            'message' => 'Notification sent successfully',
        ]);
    }

    private function errorResponse(string $getMessage)
    {
        return Response::json([
            'success' => false,
            'message' => $getMessage,
        ], 400);
    }

    /** Send location to Telegram chat
     * @param SendLocationRequest $request
     * @param string $chatId
     * @return JsonResponse
     */
    public function location(SendLocationRequest $request, string $chatId)
    {
        try {
            $payload = (object)$request->validated();
            $t = TelegramLocation::create()
                ->latitude($payload->latitude)
                ->longitude($payload->longitude)
                ->token($payload->token ?? $this->token);
            Notification::route('telegram', $chatId)->notify(new TelegramNotification($t));

            return $this->successResponse();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /** Send poll to Telegram chat
     * @param SendPollRequest $request
     * @param string $chatId
     * @return JsonResponse
     */
    public function poll(SendPollRequest $request, string $chatId)
    {
        try {
            $payload = (object)$request->validated();
            $t = TelegramPoll::create()
                ->question($payload->question)
                ->choices($payload->choices)
                ->token($payload->token ?? $this->token);
            Notification::route('telegram', $chatId)->notify(new TelegramNotification($t));

            return $this->successResponse();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /** Send poll to Telegram chat
     * @param SendContactRequest $request
     * @param string $chatId
     * @return JsonResponse
     */
    public function contact(SendContactRequest $request, string $chatId)
    {
        try {
            $payload = (object)$request->validated();
            $t = TelegramContact::create()
                ->phoneNumber($payload->phone_number)
                ->firstName($payload->first_name)
                ->lastName($payload->last_name ?? null)
                ->vcard($payload->vcard ?? null)
                ->token($payload->token ?? $this->token);
            Notification::route('telegram', $chatId)->notify(new TelegramNotification($t));

            return $this->successResponse();
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
