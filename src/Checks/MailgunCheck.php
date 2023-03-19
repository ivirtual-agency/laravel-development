<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Composer\InstalledVersions;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class MailgunCheck extends Check
{
    /**
     * Check if mailgun is used and correctly configured.
     */
    public function run(): Result
    {
        if (config('mail.default') !== 'mailgun') {
            return Result::make()
                ->ok('Mailgun is not being used.')
                ->shortSummary('Not used');
        }

        if (
            ! InstalledVersions::isInstalled('symfony/mailgun-mailer') ||
            ! InstalledVersions::isInstalled('symfony/http-client')
        ) {
            Result::make()
                ->failed('Symfony\'s Mailgun Mailer transport drivers not installed. Run: composer require symfony/mailgun-mailer symfony/http-client')
                ->shortSummary('Drivers not installed');
        }

        if (! config('services.mailgun.domain')) {
            return Result::make()
                ->failed('Mailgun domain is not set.')
                ->shortSummary('Domain not set.');
        }

        return ! config('services.mailgun.secret')
            ? Result::make()
                ->failed('Mailgun secret is not set.')
                ->shortSummary('Domain not set.')
            : Result::make()->ok('Ok');
    }
}
