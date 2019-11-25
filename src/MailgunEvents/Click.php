<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Spatie\Mailcoach\Models\CampaignSend;

class Click extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        return false;
    }

    public function handle(CampaignSend $campaignSend)
    {
        // TODO: Implement handle() method.
    }
}
