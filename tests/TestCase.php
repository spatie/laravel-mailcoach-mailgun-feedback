<?php

namespace Spatie\MailgunFeedback\Tests;

use CreateWebhookCallsTable;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\EmailCampaigns\EmailCampaignsServiceProvider;
use Spatie\MailgunFeedback\MailgunFeedbackServiceProvider;
use Spatie\MailgunFeedback\MailgunWebhookConfig;
use Spatie\WebhookClient\WebhookClientServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ . '/../vendor/spatie/laravel-email-campaigns/tests/database/factories');

        Route::emailCampaigns('email-campaigns');

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            EmailCampaignsServiceProvider::class,
            MailgunFeedbackServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('mail.driver', 'log');
    }

    protected function setUpDatabase()
    {
        include_once __DIR__ . '/../vendor/spatie/laravel-webhook-client/database/migrations/create_webhook_calls_table.php.stub';
        (new CreateWebhookCallsTable())->up();

        include_once __DIR__ . '/../vendor/spatie/laravel-email-campaigns/database/migrations/create_email_campaign_tables.php.stub';
        (new \CreateEmailCampaignTables())->up();
    }

    public function getStub(string $name): array
    {
        $content = file_get_contents(__DIR__ . "/stubs/{$name}.json");

        return json_decode($content, true);
    }

    public function addValidSignature(array $payloadContent = []): array
    {
        return array_merge($payloadContent,
            [
                "signature" => [
                    "timestamp" => "1529006854",
                    "token" => "a8ce0edb2dd8301dee6c2405235584e45aa91d1e9f979f3de0",
                    "signature" => hash_hmac(
                        'sha256',
                        sprintf('%s%s', '1529006854', 'a8ce0edb2dd8301dee6c2405235584e45aa91d1e9f979f3de0'),
                        MailgunWebhookConfig::get()->signingSecret,
                    ),
                ],
                "event-data" => [
                    "event" => "opened",
                    "timestamp" => 1529006854.329574,
                    "id" => "DACSsAdVSeGpLid7TN03WA",
                ],
            ]);
    }
}
