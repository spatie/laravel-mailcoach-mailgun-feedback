<?php

namespace Spatie\MailCoachMailgunFeedback;

use Spatie\MailCoach\Models\CampaignSend;
use Spatie\WebhookClient\ProcessWebhookJob;

class ProcessMailgunWebhookJob extends ProcessWebhookJob
{
    public function handle()
    {
        $payload = $this->webhookCall->payload;
        $eventData = $payload['event-data'];

        /** @var \Spatie\MailCoach\Models\CampaignSend $campaignSend */
        $campaignSend = CampaignSend::findByTransportMessageId($eventData['id']);

        if (! $campaignSend) {
            return;
        }

        if ($eventData['event'] !== 'failed') {
            return;
        }

        $campaignSend->markAsBounced($eventData['severity']);
    }
}
