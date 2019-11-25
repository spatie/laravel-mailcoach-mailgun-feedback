<?php

namespace Spatie\MailcoachMailgunFeedback;

use Spatie\MailcoachMailgunFeedback\MailgunEvents\Click;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\Complaint;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\MailgunEvent;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\Open;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\Other;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\PermanentBounce;

class MailgunEventFactory
{
    protected static $mailgunEvents = [
        Click::class,
        Complaint::class,
        Open::class,
        PermanentBounce::class,
    ];

    public static function createForPayload(array $payload): MailgunEvent
    {
        $mailgunEvent = collect(static::$mailgunEvents)
            ->map(function (string $mailgunEventClass) use ($payload) {
                return new $mailgunEventClass($payload);
            })
            ->first(function (MailgunEvent $mailgunEvent) use ($payload) {
                return $mailgunEvent->canHandlePayload();
            });

        return $mailgunEvent ?? new Other($payload);
    }
}
