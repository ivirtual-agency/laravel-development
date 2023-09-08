<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Composer\InstalledVersions;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class AmazonSESCheck extends Check
{
    /**
     * Check if mailgun is used and correctly configured.
     */
    public function run(): Result
    {
        if (
            config('mail.default') !== 'ses' &&
            config('mail.default') !== 'sesv2'
        ) {
            return Result::make()
                ->warning('AWS SES is not being used.')
                ->shortSummary('Not used');
        }

        if (!InstalledVersions::isInstalled('aws/aws-sdk-php')) {
            Result::make()
                ->failed('AWS sdk is not installed. Run: composer require aws/aws-sdk-php')
                ->shortSummary('Driver not installed');
        }

        if (
            !config('services.ses.key') || 
            !config('services.ses.secret')
        ) {
            return Result::make()
                ->failed('AWS key or secret not set.')
                ->shortSummary('Key or secret not set.');
        }

        return Result::make()->ok('Ok');
    }
}
