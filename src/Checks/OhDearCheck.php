<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Composer\InstalledVersions;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class OhDearCheck extends Check
{
    /**
     * Check if Laravel Horizon is installed and the license is correctly configured.
     */
    public function run(): Result
    {
        // Check that oh dear app configuration is correctly set up.

        // OH_DEAR_HEALTH_CHECK_SECRET

        // OH_DEAR_API_TOKEN

        // OH_DEAR_SITE_ID

        // Check if the package is installed in the project.
        if (! $this->hasLaravelHorizonInstalled()) {
            return Result::make()
                ->failed('Laravel horizon is not installed in this project.')
                ->shortSummary('Horizon not installed');
        }

        $schedulerQueue = config('schedule-monitor.oh_dear.queue');

        if ($schedulerQueue !== 'OH_DEAR') {
            return Result::make()
                ->meta([
                    'actual' => $schedulerQueue,
                    'expexted' => 'OH_DEAR',
                ])
                ->failed('Schedule monitor oh dear queue is not correcty set.')
                ->shortSummary($schedulerQueue ?? '-');
        }

        if (! in_array(
            'OH_DEAR',
            config('horizon.defaults.supervisor-1.queue', [])
        )) {
            return Result::make()
                ->failed('Horizon queue does not have "OH_DEAR" added.');
        }

        if (! is_int(config('scheduler-monitor.oh_dear.site_id'))) {
            return Result::make()
                ->failed('Oh dear site id not added.')
                ->shortSummary(config('scheduler-monitor.oh_dear.site_id', '-'));
        }

        if (! is_int(config('scheduler-monitor.oh_dear.api_token'))) {
            return Result::make()
                ->failed('Oh dear api token not added.');
        }

        return parent::run();
    }

    /**
     * Check if the project has laravel horizon installed.
     */
    private function hasLaravelHorizonInstalled(): bool
    {
        return InstalledVersions::isInstalled('laravel/horizon');
    }
}