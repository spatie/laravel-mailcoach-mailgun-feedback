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

        if (!$campaignSend = $this->getCampaignSend()) {
            return;
        };

        $mailgunEvent = MailgunEventFactory::createForPayload($payload);

        $mailgunEvent->handle($campaignSend);
    }

    protected function getCampaignSend(): ?CampaignSend
    {
        $messageId = Arr::get($this->webhookCall->payload, 'event-data.message.headers.message-id');

        if (!$messageId) {
            return null;
        }

        return CampaignSend::findByTransportMessageId($messageId);
    }
}
