<?php

namespace Spatie\MailgunFeedback;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\WebhookProcessor;
use Spatie\WebhookClient\WebhookProfile\ProcessEverythingWebhookProfile;

class MailgunWebhookController
{
    public function __invoke(Request $request)
    {
        $webhookConfig = MailgunWebhookConfig::get();

        (new WebhookProcessor($request, $webhookConfig))->process();

        return response()->json(['message' => 'ok']);
    }
}
