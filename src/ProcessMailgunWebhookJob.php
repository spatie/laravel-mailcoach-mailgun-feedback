<?php

namespace Spatie\MailcoachMailgunFeedback;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Models\CampaignSend;
use Spatie\WebhookClient\ProcessWebhookJob;

class ProcessMailgunWebhookJob extends ProcessWebhookJob
{
    public function handle()
    {
        $payload = $this->webhookCall->payload;

        if (Arr::get($payload, 'event-data.event') !== 'failed') {
            return;
        }

        if (Arr::get($payload, 'event-data.severity') !== 'permanent') {
            return;
        }

        $messageId = Arr::get($payload, 'event-data.message.headers.message-id');

        if (! $messageId) {
            return;
        }

        /** @var \Spatie\Mailcoach\Models\CampaignSend $campaignSend */
        $campaignSend = CampaignSend::findByTransportMessageId($messageId);

        if (!$campaignSend) {
            return;
        }

        $campaignSend->markAsBounced();
    }
}
