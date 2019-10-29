# Process feedback for email campaigns sent using Mailgun

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-email-campaigns-mailgun-feedback.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-email-campaigns-mailgun-feedback)
[![Build Status](https://img.shields.io/travis/spatie/laravel-email-campaigns-mailgun-feedback/master.svg?style=flat-square)](https://travis-ci.org/spatie/laravel-email-campaigns-mailgun-feedback)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/laravel-email-campaigns-mailgun-feedback.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/laravel-email-campaigns-mailgun-feedback)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-email-campaigns-mailgun-feedback.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-email-campaigns-mailgun-feedback)

This package is an add on for [spatie/laravel-email-campaigns](https://github.com/spatie/laravel-email-campaigns) that can process the feedback given by Mailgun.

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-email-campaigns-mailgun-feedback
```

Under the hood this package uses [spatie/laravel-webhook-client](https://github.com/spatie/laravel-email-campaigns) to process handle webhooks. You are required to publish its migration to create the `webhook_calls` table. You can skip this step if your project already uses the `laravel-webhook-client` package directly.

```php
php artisan vendor:publish --provider="Spatie\WebhookClient\WebhookClientServiceProvider" --tag="migrations"
```

After the migration has been published, you can create the `webhook_calls` table by running the migrations:

```php
php artisan migrate
```

At Mailgun you must [configure a new webhook](https://www.mailgun.com/blog/a-guide-to-using-mailguns-webhooks/).

In the `email-campaigns` config file you must add this section.

```php
// in config/email-campaigns.php

    'mailgun_feedback' => [
        'signing_secret' => env('MAILGUN_SIGNING_SECRET'),
   ],
```

In your `.env` you must add a key `MAILGUN_SIGNING_SECRET` with the Mailgun signing secret you'll find at the Mailgun dashboard as its value. 

You must use this route macro somewhere in your routes file. Replace `'mailgun-feeback'` with the url you specified at Mailgun when setting up the webhook there.

```php
Route::mailgunFeedback('mailgun-feedback');
```

## Usage

After following the installation instruction, your project is ready to handle feedback by Mailgun on the sent email campaigns.

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
    
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
