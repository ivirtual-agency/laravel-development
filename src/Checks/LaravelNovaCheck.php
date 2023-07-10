<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Composer\InstalledVersions;
use Laravel\Nova\Nova;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class LaravelNovaCheck extends Check
{
    /**
     * Check if Laravel Nova is installed and the license is correctly configured.
     */
    public function run(): Result
    {
        // Check if the package is installed in the project.
        if (! $this->hasLaravelNovaInstalled()) {
            return Result::make()
                ->ok('Laravel nova is not installed in this project.')
                ->shortSummary('Not installed');
        }

        if (empty(config('nova.license_key'))) {
            return Result::make()->failed('License key not provided.');
        }

        // Check Nova license via http.
        $response = Nova::checkLicense();

        return $response->status() == 204
            ? Result::make()->ok()
            : Result::make()->failed($response->json('message'));
    }

    /**
     * Check if the project has laravel nova installed.
     */
    private function hasLaravelNovaInstalled(): bool
    {
        return InstalledVersions::isInstalled('laravel/nova');
    }
}
