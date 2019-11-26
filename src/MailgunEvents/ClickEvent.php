<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Models\CampaignSend;

class ClickEvent extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'clicked';
    }

    public function handle(CampaignSend $campaignSend)
    {
        $url = Arr::get($this->payload, 'event-data.url');

        $campaignSend->registerClick($url);
    }
}
