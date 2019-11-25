<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Spatie\Mailcoach\Models\CampaignSend;

class Complaint extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'complained';
    }

    public function handle(CampaignSend $campaignSend)
    {
        $campaignSend->complaintReceived();
    }
}
