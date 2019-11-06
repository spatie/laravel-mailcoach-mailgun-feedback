<?php

namespace Spatie\MailgunFeedback;

use Spatie\EmailCampaigns\Models\CampaignSend;
use Spatie\WebhookClient\ProcessWebhookJob;

class ProcessMailgunWebhookJob extends ProcessWebhookJob
{
    public function handle()
    {
        $payload = $this->webhookCall->payload;
        $eventData = $payload['event-data'];

        /** @var \Spatie\EmailCampaigns\Models\CampaignSend $campaignSend */
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
