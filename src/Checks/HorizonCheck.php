<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Composer\InstalledVersions;
use Spatie\Health\Checks\Checks;
use Spatie\Health\Checks\Result;

class HorizonCheck extends Checks\HorizonCheck
{
    /**
     * Check if Laravel Horizon is installed and the license is correctly configured.
     */
    public function run(): Result
    {
        // Check if the package is installed in the project.
        if (! $this->hasLaravelHorizonInstalled()) {
            return Result::make()
                ->ok('Laravel horizon is not installed in this project.')
                ->shortSummary('Not installed');
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
