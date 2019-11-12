<?php

namespace Spatie\MailcoachMailgunFeedback;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MailcoachMailgunFeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::macro('mailgunFeedback', function (string $url) {
            return Route::post($url, '\Spatie\MailcoachMailgunFeedback\MailgunWebhookController');
        });

        Event::listen(MessageSent::class, StoreTransportMessageId::class);
    }
}
