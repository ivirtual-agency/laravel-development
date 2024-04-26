<?php

namespace iVirtual\LaravelDevelopment;

use Composer\InstalledVersions;
use Illuminate\Support\Facades\Schedule;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

class LaravelDevelopment
{
    /**
     * Register the laravel development health checks.
     */
    public function registerHealthChecks(bool $runningInConsole = false): void
    {
        ($healthCheck = HealthChecks::new())->registerRequiredHealthChecks();

        if (!$runningInConsole) {
            $healthCheck->registerOhDearAppHealthChecks();
        }
    }

    /**
     * Configure the default schedule for the project.
     *
     * @link https://github.com/spatie/laravel-schedule-monitor/
     */
    public function schedule(): void
    {
        Schedule::command('model:prune')->daily()->doNotMonitor();

        Schedule::command('model:prune', [
            '--model' => MonitoredScheduledTaskLogItem::class,
        ])
            ->daily()
            ->doNotMonitor();

        if (InstalledVersions::isInstalled('laravel/nova')) {
            Schedule::call(new \Laravel\Nova\Fields\Attachments\PruneStaleAttachments)
                ->daily()
                ->doNotMonitor();
        }

        if (InstalledVersions::isInstalled('laravel/horizon')) {
            Schedule::command('horizon:snapshot')
                ->everyFiveMinutes()
                ->doNotMonitor();
        }
    }
}
