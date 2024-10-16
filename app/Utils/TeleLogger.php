<?php

namespace App\Utils;

use Exception;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Telegram\Bot\Api;
use Telegram\Bot\FileUpload\InputFile;

final class TeleLogger
{
    /**
     * @throws TelegramSDKException
     */
    public static function logException(Throwable $e, string $channel = 'dashboard'): void
    {
        try {
            $chunkSize = 4000; // Adjust the chunk size based on the file info length

            $exceptionData = [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'line' => $e->getLine(),
            ];

            $exceptionDataJson = json_encode($exceptionData);
            $fileInfo = $e->getFile();
            $errorMessage = Str::limit($exceptionDataJson, $chunkSize - mb_strlen($fileInfo));

            // Send the initial message
            self::sendTeleMessage($fileInfo, $channel, 'critical', data: $errorMessage);
            // send the full logged error to the channel using the method logExceptionJson
            self::logFullExceptionJson($e, channel: $channel);
            // TODO enable if you want to send the full logged error to the channel by multiple messages, disabled for now to decrease the server tension
            // Split the exception data into chunks
            // if (($chunkSize - mb_strlen($fileInfo)) < mb_strlen($exceptionDataJson)) {
            //     $exceptionChunks = mb_str_split(mb_substr($exceptionDataJson, $chunkSize - mb_strlen($fileInfo)), ($chunkSize - mb_strlen($fileInfo)) - 2);
            //
            //     // Send the remaining chunks
            //     foreach ($exceptionChunks as $exceptionChunk) {
            //         self::sendTeleMessage($fileInfo, $channel, 'critical', data: $exceptionChunk);
            //     }
            // }
        } catch (Throwable $th) {
            $message = Str::limit($th->getMessage(), 3900);
            TeleLogger::logInfo($message, __METHOD__, $channel);
        }
    }

    /**
     * @throws TelegramSDKException
     */
    public static function logInfo(mixed $data, string $method, string $channel = 'dashboard'): void
    {
        $chunkSize = 3900; // Adjust the chunk size based on the file info length

        try {
            $dataJson = json_encode($data);
            $infoMessage = Str::limit($dataJson, $chunkSize - mb_strlen($method));

            // Send the initial message
            self::sendTeleMessage($method, $channel, data: $infoMessage);

            // Split the exception data into chunks
            if ($chunkSize < mb_strlen($dataJson)) {
                $messageChunks = mb_str_split(mb_substr($dataJson, $chunkSize - mb_strlen($method)), $chunkSize - mb_strlen($method));

                // Send the remaining chunks
                foreach ($messageChunks as $messageChunk) {
                    self::sendTeleMessage($method, $channel, data: $messageChunk);
                }
            }
        } catch (Exception $e) {
            TeleLogger::logException($e);
        }
    }

    /**
     * @throws TelegramSDKException
     */
    public static function logFullExceptionJson(Throwable $e, string $channel = 'dashboard'): void
    {
        $exceptionData = [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ];

        $timestamp = date('d-m-Y H:m:s');
        $logJsonFilename = 'e_' . $timestamp . '.json';
        $logJsonPath = storage_path('tempStorage/app/') . $logJsonFilename;
        if (! file_exists(storage_path('tempStorage/app/'))) {
            mkdir('tempStorage/app/', 0777, true);
        }
        Storage::disk('local')->put($logJsonPath, json_encode($exceptionData));
        $telegram = new Api(config('logging.channels.' . $channel . '.token'));

        $telegram->sendDocument([
            'chat_id' => config('logging.channels.' . $channel . '.chat_id'),
            'document' => InputFile::create($logJsonPath),
            'caption' => 'full log as: ' . $logJsonFilename,
        ]);
        Storage::disk('local')->delete($logJsonPath);
    }

    /**
     * @throws TelegramSDKException
     */
    public static function sendTeleMessage(string $message, string $channel = 'dashboard', string $type = 'info', mixed $data = null): void
    {
        $telegram = new Api(config('logging.channels.' . $channel . '.token'));
        $environment = env('APP_ENV', 'production');
        $jsonData = json_encode($data ?? []);
        $timestamp = date('d-m-Y H:m:s');
        $telegram->sendMessage([
            'chat_id' => config('logging.channels.' . $channel . '.chat_id'),
            'text' => "`Logs of ({$timestamp})`\n\n*Type:* {$type}\n*Environment:* {$environment}\n\n\n*Message:* \n```md\n{$message}``` \n\n*Data:* \n```json\n{$jsonData}```",
            'parse_mode' => 'MarkdownV2',
        ]);
    }
}
