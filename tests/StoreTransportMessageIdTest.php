<?php

namespace Spatie\MailgunFeedback\Tests;

use Spatie\EmailCampaigns\Jobs\SendMailJob;
use Spatie\EmailCampaigns\Models\CampaignSend;

class StoreTransportMessageIdTest extends TestCase
{
    /** @test * */
    public function it_stores_the_message_id_from_the_transport()
    {
        $pendingSend = factory(CampaignSend::class)->create();

        dispatch(new SendMailJob($pendingSend));

        tap($pendingSend->fresh(), function (CampaignSend $campaignSend) {
            $this->assertNotNull($campaignSend->transport_message_id);
        });
    }
}
