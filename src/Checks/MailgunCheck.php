<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Composer\InstalledVersions;
use Encodia\Health\Checks\EnvVars;
use Spatie\Health\Checks\Result;

class MailgunCheck extends EnvVars
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

        $this->requireVarsForEnvironment(
            'production',
            ['MAILGUN_SECRET']
        );

        return parent::run();
    }
}
