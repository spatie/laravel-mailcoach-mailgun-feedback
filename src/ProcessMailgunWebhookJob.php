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

        $event = Arr::get($payload, 'event-data.event');

        if (!$campaignSend = $this->getCampaignSend()) {
            return;
        };

        if ($event === 'failed') {
            $this->handleBounce($campaignSend, $payload);

            return;
        }

        if ($event === 'complained') {
            $this->handleComplaint($campaignSend, $payload);

            return;
        }
    }

    protected function handleBounce(CampaignSend $campaignSend, array $payload)
    {
        if (Arr::get($payload, 'event-data.severity') !== 'permanent') {
            return;
        }

        $campaignSend->markAsBounced();
    }

    protected function handleComplaint(CampaignSend $campaignSend)
    {
        $campaignSend->complaintReceived();
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
