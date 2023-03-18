<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Composer\InstalledVersions;
use Encodia\Health\Checks\EnvVars;
use Laravel\Nova\Nova;
use Spatie\Health\Checks\Result;
use Spatie\Health\Enums\Status;

class LaravelNovaCheck extends EnvVars
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

        $result = $this->checkForEnvironmentVariable();

        // If parent run fails return it,
        // If parent check is ok continue.
        if (! $result->status->equals(Status::ok())) {
            return $result;
        }

        // Check Nova license via http.
        $response = Nova::checkLicense();

        return $response->status() == 204
            ? $result->ok()
            : $result->failed($response->json('message'));
    }

    /**
     * Check if the project has laravel nova installed.
     */
    private function hasLaravelNovaInstalled(): bool
    {
        return InstalledVersions::isInstalled('laravel/nova');
    }

    /**
     * Check if laravel nova environment variable is set in production.
     */
    private function checkForEnvironmentVariable(): Result
    {
        $this->requireVarsForEnvironment(
            'production',
            ['NOVA_LICENSE_KEY']
        );

        return parent::run();
    }
}
