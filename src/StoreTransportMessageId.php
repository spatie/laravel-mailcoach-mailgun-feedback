<?php

namespace Spatie\MailgunFeedback;

use Illuminate\Mail\Events\MessageSent;

class StoreTransportMessageId
{
    public function handle(MessageSent $event)
    {
        if (! isset($event->data['campaignSend'])) {
            return;
        }

        /** @var \Spatie\EmailCampaigns\Models\CampaignSend $campaignSend */
        $campaignSend = $event->data['campaignSend'];

        $transportMessageId = $event->message->getHeaders()->get('X-Mailgun-Message-ID')->getFieldBody();

        $campaignSend->storeTransportMessageId($transportMessageId);
    }
}
