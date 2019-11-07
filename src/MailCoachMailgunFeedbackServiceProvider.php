<?php

namespace Spatie\MailCoachMailgunFeedback;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MailCoachMailgunFeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::macro('MailCoachMailgunFeedback', function (string $url) {
            return Route::post($url, '\Spatie\MailCoachMailgunFeedback\MailgunWebhookController');
        });

        Event::listen(MessageSent::class, StoreTransportMessageId::class);
    }
}
