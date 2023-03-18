<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Spatie\Health\Checks\Checks;
use Spatie\Health\Checks\Result;

class FlareErrorOccurrenceCountCheck extends Checks\FlareErrorOccurrenceCountCheck
{
    /**
     * Check if Flare (Spatie Ignition) is correctly configured and has a low error count.
     */
    public function run(): Result
    {
        $apiToken = config('services.flare.api_token');
        $projectId = config('services.flare.project_id');

        if (is_null($apiToken)) {
            return Result::make()
                ->warning('Flare API token not provided.')
                ->shortSummary('Not installed');
        }

        if (is_null($projectId)) {
            return Result::make()
                ->warning('Flare project ID not provided.')
                ->shortSummary('Not installed');
        }

        $this
            ->apiToken($apiToken)
            ->projectId($projectId)
            ->warnWhenMoreErrorsReceivedThan(50)
            ->failWhenMoreErrorsReceivedThan(100);

        return parent::run();
    }
}
