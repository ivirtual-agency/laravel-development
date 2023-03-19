<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class FlareCheck extends Check
{
    /**
     * Check if Flare (Spatie Ignition) is installed and the correctly configured.
     */
    public function run(): Result
    {
        if (! $this->isFlareInLoggingChannel()) {
            return Result::make()
                ->failed('Flare is not in the logging channel.')
                ->shortSummary('Not installed');
        }

        // Check for Flare key.
        return ! config('flare.key')
            ? Result::make()
                ->failed('Flare key is not set.')
                ->shortSummary('not set')
            : Result::make()->ok('Ok');
    }

    /**
     * Check if flare is in the logging channels.
     */
    private function isFlareInLoggingChannel(): bool
    {
        // Check if logging is set to flare.
        if (config('logging.default') === 'flare') {
            return true;
        }

        // Check if logging is set to stack and flare is in channels.
        return config('logging.default') === 'stack'
            && in_array(
                'flare',
                config('logging.channels.stack.channels', [])
            );
    }
}
