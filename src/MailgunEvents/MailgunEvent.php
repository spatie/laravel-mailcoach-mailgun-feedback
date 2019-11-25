<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Models\CampaignSend;

abstract class MailgunEvent
{
    /** @var array */
    protected $payload;

    /** @var string */
    protected $event;

    public function __construct(array $payload)
    {
        $this->payload = $payload;

        $this->event = Arr::get($payload, 'event-data.event');
    }

    abstract public function canHandlePayload(): bool;

    abstract public function handle(CampaignSend $campaignSend);
}
