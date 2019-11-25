<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Spatie\Mailcoach\Models\CampaignSend;

class Other extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        return true;
    }

    public function handle(CampaignSend $campaignSend)
    {
    }
}
