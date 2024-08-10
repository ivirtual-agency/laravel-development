<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class LoggingCheck extends Check
{
    /**
     * Check if logging is correctly configured.
     */
    public function run(): Result
    {
        if (config('logging.default') !== 'stack') {
            return Result::make()->failed('Logging channel is not stack.');
        }

        if (! in_array('daily', config('logging.channels.stack.channels'))) {
            return Result::make()->failed('Logging daily channel not added.');
        }

        if (in_array('single', config('logging.channels.stack.channels'))) {
            return Result::make()->failed('Logging single channel added.');
        }

        // Check for Sentry DSN.
        return ! config('sentry.dsn')
            ? Result::make()
                ->failed('Sentry DSN is not set.')
                ->shortSummary('not set')
            : Result::make()->ok('Ok');
    }
}
