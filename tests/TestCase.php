<?php

namespace Spatie\MailgunFeedback\Tests;

use CreateWebhookCallsTable;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MailgunFeedback\MailgunFeedbackServiceProvider;
use Spatie\WebhookClient\WebhookClientServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__ .'/../vendor/spatie/laravel-email-campaigns/tests/database/factories');

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            MailgunFeedbackServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUpDatabase()
    {
        include_once __DIR__.'/../vendor/spatie/laravel-webhook-client/database/migrations/create_webhook_calls_table.php.stub';
        (new CreateWebhookCallsTable())->up();

        include_once __DIR__.'/../vendor/spatie/laravel-email-campaigns/database/migrations/create_email_campaign_tables.php.stub';
        (new \CreateEmailCampaignTables())->up();
    }
}
