<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class OhDearCheck extends Check
{
    /**
     * Check if Laravel Horizon is installed and the license is correctly configured.
     */
    public function run(): Result
    {
        if (! config('schedule-monitor.oh_dear.site_id')) {
            return Result::make()
                ->failed('Oh dear site id not added.')
                ->shortSummary(config('schedule-monitor.oh_dear.site_id', '-'));
        }

        if (! config('schedule-monitor.oh_dear.api_token')) {
            return Result::make()->failed('Oh dear api token not added.');
        }

        if (! config('health.oh_dear_endpoint.secret')) {
            return Result::make()->failed('Oh dear endpoint secret not added.');
        }

        return Result::make()->ok('Ok');
    }
}
