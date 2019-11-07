<?php

namespace Spatie\MailCoachMailgunFeedback\Tests;

use Spatie\MailCoach\Models\CampaignSend;
use Spatie\MailCoach\Models\CampaignSendBounce;
use Spatie\MailCoachMailgunFeedback\ProcessMailgunWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessMailgunWebhookJobTest extends TestCase
{
    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    private $webhookCall;

    /** @var \Spatie\MailCoach\Models\CampaignSend */
    private $campaignSend;

    public function setUp(): void
    {
        parent::setUp();

        $this->webhookCall = WebhookCall::create([
            'name' => 'mailgun',
            'payload' => $this->getStub('webhookContent'),
        ]);

        $this->campaignSend = factory(CampaignSend::class)->create([
            'transport_message_id' => 'G9Bn5sl1TC6nu79C8C0bwg',
        ]);
    }

    /** @test */
    public function it_processes_a_mailgun_webhook_call()
    {
        $job = new ProcessMailgunWebhookJob($this->webhookCall);

        $job->handle();

        $this->assertEquals(1, CampaignSendBounce::count());
        $this->assertEquals('permanent', CampaignSendBounce::first()->severity);
        $this->assertTrue($this->campaignSend->is(CampaignSendBounce::first()->campaignSend));
    }

    /** @test */
    public function it_only_saves_when_event_is_a_failure()
    {
        $data =$this->webhookCall->payload;
        $data['event-data']['event'] = 'success';

        $this->webhookCall->update([
            'payload' => $data,
        ]);

        $job = new ProcessMailgunWebhookJob($this->webhookCall);

        $job->handle();

        $this->assertEquals(0, CampaignSendBounce::count());
    }

    /** @test */
    public function it_does_nothing_when_it_cannot_find_the_transport_message_id()
    {
        $data = $this->webhookCall->payload;
        $data['event-data']['id'] = 'some-other-id';

        $this->webhookCall->update([
            'payload' => $data,
        ]);

        $job = new ProcessMailgunWebhookJob($this->webhookCall);

        $job->handle();

        $this->assertEquals(0, CampaignSendBounce::count());
    }
}
