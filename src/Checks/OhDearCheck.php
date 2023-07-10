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
        if (! config('schedule-monitor.oh_dear.site_id')) {
            return Result::make()
                ->failed('Oh dear site id not added.')
                ->shortSummary(config('schedule-monitor.oh_dear.site_id', '-'));
        }

        if (! config('schedule-monitor.oh_dear.api_token')) {
            return Result::make()
                ->failed('Oh dear api token not added.');
        }

        if (! $this->hasLaravelHorizonInstalled()) {
            return Result::make()
                ->ok('Ok')
                ->shortSummary('Horizon not installed');;
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

        return Result::make()->ok('Ok');
    }

    /**
     * Check if the project has laravel horizon installed.
     */
    private function hasLaravelHorizonInstalled(): bool
    {
        return InstalledVersions::isInstalled('laravel/horizon');
    }
}
