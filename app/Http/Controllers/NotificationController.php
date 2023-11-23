<?php

namespace App\Http\Controllers;

use App\Data\Payload;
use App\Enums\PayloadSelector;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\FileRequest;
use App\Http\Requests\LocationRequest;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\PollRequest;
use App\Notifications\TelegramNotification;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use JsonException;
use NotificationChannels\Telegram\TelegramBase;
use NotificationChannels\Telegram\TelegramContact;
use NotificationChannels\Telegram\TelegramFile;
use NotificationChannels\Telegram\TelegramLocation;
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
     * @param MessageRequest $request
     * @param string $chatId
     * @return JsonResponse
     * @throws JsonException
     */
    public function github(Request $request, string $chatId)
    {
        try {
            $event = $request->header('X-GitHub-Event');

            $payload = PayloadSelector::tryFrom($event)
                ?->getPayload(data: $request->all());

            if (!$payload) {
                return $this->errorResponse('Invalid event type');
            }

            if (!$payload->shouldNotify()) {
                return $this->errorResponse('This event should not be notified');
            }

            $t = TelegramFile::create()
                ->content($payload->content())
                ->photo($payload->image())
                ->button('View on Github', $payload->url());

            return $this->sendNotification($chatId, $t);
        } catch (Exception $e) {
            if (App::isProduction())
                return $this->errorResponse($e->getMessage());
            else
                throw $e;
        }
    }

    private function errorResponse(string $getMessage): JsonResponse
    {
        return Response::json([
            'success' => false,
            'message' => $getMessage,
        ], 400);
    }

    private function sendNotification(string $chatId, TelegramBase $base): JsonResponse
    {
        Notification::route('telegram', $chatId)->notify(new TelegramNotification($base));
        return $this->successResponse();
    }

    private function successResponse(): JsonResponse
    {
        return Response::json([
            'success' => true,
            'message' => 'Notification sent successfully',
        ]);
    }

    /** Send file attachment to Telegram chat
     * @param FileRequest $request
     * @param string $chatId
     * @return JsonResponse
     */
    public function file(FileRequest $request, string $chatId)
    {
        try {
            $payload = (object)$request->validated();
            $t = TelegramFile::create()
                ->content($payload->content)
                ->file($payload->file['path'], $payload->file['type'], $payload->file['name'] ?? null)
                ->token($payload->token ?? $this->token);

            foreach ($payload->buttons ?? [] as $button) {
                $t->button($button['text'], $button['url']);
            }
            return $this->sendNotification($chatId, $t);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /** Send location to Telegram chat
     * @param LocationRequest $request
     * @param string $chatId
     * @return JsonResponse
     */
    public function location(LocationRequest $request, string $chatId)
    {
        try {
            $payload = (object)$request->validated();
            $t = TelegramLocation::create()
                ->latitude($payload->latitude)
                ->longitude($payload->longitude)
                ->token($payload->token ?? $this->token);

            return $this->sendNotification($chatId, $t);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /** Send poll to Telegram chat
     * @param PollRequest $request
     * @param string $chatId
     * @return JsonResponse
     */
    public function poll(PollRequest $request, string $chatId)
    {
        try {
            $payload = (object)$request->validated();
            $t = TelegramPoll::create()
                ->question($payload->question)
                ->choices($payload->choices)
                ->token($payload->token ?? $this->token);


            return $this->sendNotification($chatId, $t);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /** Send poll to Telegram chat
     * @param ContactRequest $request
     * @param string $chatId
     * @return JsonResponse
     */
    public function contact(ContactRequest $request, string $chatId)
    {
        try {
            $payload = (object)$request->validated();
            $t = TelegramContact::create()
                ->phoneNumber($payload->phone_number)
                ->firstName($payload->first_name)
                ->lastName($payload->last_name ?? null)
                ->vcard($payload->vcard ?? '')
                ->token($payload->token ?? $this->token);

            return $this->sendNotification($chatId, $t);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
