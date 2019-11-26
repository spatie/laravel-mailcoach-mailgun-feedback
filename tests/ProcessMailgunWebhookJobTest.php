<?php

namespace Spatie\MailcoachMailgunFeedback\Tests;

use Spatie\Mailcoach\Enums\CampaignSendFeedbackType;
use Spatie\Mailcoach\Models\CampaignLink;
use Spatie\Mailcoach\Models\CampaignOpen;
use Spatie\Mailcoach\Models\CampaignSend;
use Spatie\Mailcoach\Models\CampaignSendFeedbackItem;
use Spatie\MailcoachMailgunFeedback\ProcessMailgunWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessMailgunWebhookJobTest extends TestCase
{
    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    private $webhookCall;

    /** @var \Spatie\Mailcoach\Models\CampaignSend */
    private $campaignSend;

    public function setUp(): void
    {
        parent::setUp();

        $this->webhookCall = WebhookCall::create([
            'name' => 'mailgun',
            'payload' => $this->getStub('bounceWebhookContent'),
        ]);

        $this->campaignSend = factory(CampaignSend::class)->create([
            'transport_message_id' => '20130503192659.13651.20287@mg.craftremote.com',
        ]);
    }

    /** @test */
    public function it_processes_a_mailgun_bounce_webhook_call()
    {
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(1, CampaignSendFeedbackItem::count());
        $this->assertEquals(CampaignSendFeedbackType::BOUNCE, CampaignSendFeedbackItem::first()->type);
        $this->assertTrue($this->campaignSend->is(CampaignSendFeedbackItem::first()->campaignSend));
    }

    /** @test */
    public function it_processes_a_mailgun_complaint_webhook_call()
    {
        $this->webhookCall->update(['payload' => $this->getStub('complaintWebhookContent')]);
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(1, CampaignSendFeedbackItem::count());
        $this->assertEquals(CampaignSendFeedbackType::COMPLAINT, CampaignSendFeedbackItem::first()->type);
        $this->assertTrue($this->campaignSend->is(CampaignSendFeedbackItem::first()->campaignSend));
    }

    /** @test */
    public function it_processes_a_mailgun_click_webhook_call()
    {
        $this->webhookCall->update(['payload' => $this->getStub('clickWebhookContent')]);
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(1, CampaignLink::count());
        $this->assertEquals('http://example.com/signup', CampaignLink::first()->link);
        $this->assertCount(1, CampaignLink::first()->clicks);
    }

    /** @test */
    public function it_can_process_a_mailgun_open_webhook_call()
    {
        $this->webhookCall->update(['payload' => $this->getStub('openWebhookContent')]);
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertCount(1, $this->campaignSend->campaign->opens);
    }

    /** @test */
    public function it_will_not_handle_unrelated_events()
    {
        $this->webhookCall->update(['payload' => $this->getStub('otherWebhookContent')]);
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(0, CampaignLink::count());
        $this->assertEquals(0, CampaignOpen::count());
        $this->assertEquals(0, CampaignSendFeedbackItem::count());
    }

    /** @test */
    public function it_does_nothing_when_it_cannot_find_the_transport_message_id()
    {
        $data = $this->webhookCall->payload;
        $data['event-data']['message']['headers']['message-id'] = 'some-other-id';

        $this->webhookCall->update([
            'payload' => $data,
        ]);

        $job = new ProcessMailgunWebhookJob($this->webhookCall);

        $job->handle();

        $this->assertEquals(0, CampaignSendFeedbackItem::count());
    }
}
