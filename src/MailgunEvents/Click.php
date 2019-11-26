<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Spatie\Mailcoach\Models\CampaignSend;

class Click extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'clicked';
    }

    public function handle(CampaignSend $campaignSend)
    {
        $campaignSend->registerClick($this->payload['url']);
    }
}
