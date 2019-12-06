<?php

namespace Spatie\MailcoachMailgunFeedback;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Models\Send;
use Spatie\WebhookClient\ProcessWebhookJob;

class ProcessMailgunWebhookJob extends ProcessWebhookJob
{
    public function handle()
    {
        $payload = $this->webhookCall->payload;

        if (!$send = $this->getSend()) {
            return;
        };

        $mailgunEvent = MailgunEventFactory::createForPayload($payload);

        $mailgunEvent->handle($send);
    }

    protected function getSend(): ?Send
    {
        $messageId = Arr::get($this->webhookCall->payload, 'event-data.message.headers.message-id');

        if (!$messageId) {
            return null;
        }

        return Send::findByTransportMessageId($messageId);
    }
}
