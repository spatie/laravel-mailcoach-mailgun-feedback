<?php

namespace Spatie\Skeleton;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MailgunFeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::macro('mailgunFeedback', function (string $url) {
            return Route::post($url, '\Spatie\MailgunFeedback\MailgunWebhookController');
        });
    }
}
