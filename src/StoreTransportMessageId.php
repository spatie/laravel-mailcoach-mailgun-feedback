<?php

namespace Spatie\MailcoachMailgunFeedback;

use Illuminate\Mail\Events\MessageSent;

class StoreTransportMessageId
{
    public function handle(MessageSent $event)
    {
        if (! isset($event->data['campaignSend'])) {
            return;
        }

        if (! $event->message->getHeaders()->has('X-Mailgun-Message-ID')) {
            return;
        }

        /** @var \Spatie\Mailcoach\Models\CampaignSend $campaignSend */
        $campaignSend = $event->data['campaignSend'];

        $transportMessageId = $event->message->getHeaders()->get('X-Mailgun-Message-ID')->getFieldBody();

        $transportMessageId = ltrim($transportMessageId, '<');
        $transportMessageId = rtrim($transportMessageId, '>');

        $campaignSend->storeTransportMessageId($transportMessageId);
    }
}
