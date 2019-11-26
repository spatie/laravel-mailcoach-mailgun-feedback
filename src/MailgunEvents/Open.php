<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Spatie\Mailcoach\Models\CampaignSend;

class Open extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'opened';
    }

    public function handle(CampaignSend $campaignSend)
    {
        return $campaignSend->registerOpen();
    }
}
