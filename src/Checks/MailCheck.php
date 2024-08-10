<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class MailCheck extends Check
{
    /**
     * Check if mailgun is used and correctly configured.
     */
    public function run(): Result
    {
        if (is_null(config('mail.default'))) {
            return Result::make()->ok('App does not send emails.');
        }

        if (config('mail.default') !== 'ses') {
            return Result::make()
                ->failed('AWS SES is not being used.')
                ->shortSummary('Not used');
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
