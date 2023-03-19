<?php

namespace iVirtual\LaravelDevelopment;

use Composer\InstalledVersions;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Nova\Fields\Attachments\PruneStaleAttachments;

class LaravelDevelopment
{
    /**
     * Register the laravel development health checks.
     */
    public function registerHealthChecks(bool $runningInConsole = false): void
    {
        ($healthCheck = HealthChecks::new())->registerRequiredHealthChecks();

        if (! $runningInConsole) {
            $healthCheck->registerOhDearAppHealthChecks();
        }
    }

    /**
     * Configure the default schedule for the project.
     *
     * @link https://github.com/spatie/laravel-schedule-monitor/
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command('model:prune')->daily()->doNotMonitor();

        $schedule->command('model:prune', [
            '--model' => MonitoredScheduledTaskLogItem::class,
        ])
            ->daily()
            ->doNotMonitor();

        if (InstalledVersions::isInstalled('laravel/nova')) {
            $schedule->call(new PruneStaleAttachments)
                ->daily()
                ->doNotMonitor();
        }

        if (InstalledVersions::isInstalled('laravel/horizon')) {
            $schedule->command('horizon:snapshot')
                ->everyFiveMinutes()
                ->doNotMonitor();
        }
    }
}
