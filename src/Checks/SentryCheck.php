<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Composer\InstalledVersions;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class SentryCheck extends Check
{
    /**
     * Check if Flare (Spatie Ignition) is installed and the correctly configured.
     */
    public function run(): Result
    {
        if (!InstalledVersions::isInstalled('sentry/sentry-laravel')) {
            Result::make()
                ->failed('Sentry sdk is not installed. Run: composer require sentry/sentry-laravel')
                ->shortSummary('Driver not installed');
        }

        // Check for Sentry DSN.
        return ! config('sentry.dsn')
            ? Result::make()
                ->failed('Sentry DSN is not set.')
                ->shortSummary('not set')
            : Result::make()->ok('Ok');
    }
}
