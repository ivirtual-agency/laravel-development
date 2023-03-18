<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Composer\InstalledVersions;
use Encodia\Health\Checks\EnvVars;
use Spatie\Health\Checks\Result;

class MailgunCheck extends EnvVars
{
    /**
     * Check if Laravel Nova is installed and the license is correctly configured.
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

        if (is_null(config('services.mailgun.domain'))) {
            return Result::make()
                ->failed('Mailgun domain is not set.')
                ->shortSummary('Domain not set.');
        }

        $this->requireVars(['MAILGUN_SECRET']);

        return parent::run();
    }
}
