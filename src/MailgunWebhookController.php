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
        (new WebhookProcessor($request, $this->getConfig()))->process();

        return response()->json(['message' => 'ok']);
    }

    protected function getConfig(): WebhookConfig
    {
        $config = config('email-campaigns.mailgun_feedback');

        return new WebhookConfig([
            'name' => 'mailgun-feedback',
            'signing_secret' => $config['signing_secret'],
            'header_name' => $config['header_name'] ?? 'Signature',
            'signature_validator' => $config['signature_validator'] ?? MailgunSignatureValidator::class,
            'webhook_profile' =>  $config['webhook_profile'] ?? ProcessEverythingWebhookProfile::class,
            'webhook_model' => $config['webhook_model'] ?? WebhookCall::class,
            'process_webhook_job' => $config['process_webhook_job'] ?? ProcessMailgunWebhookJob::class,
        ]);
    }
}
