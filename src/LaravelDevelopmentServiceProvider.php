<?php

namespace iVirtual\LaravelDevelopment;

use iVirtual\LaravelDevelopment\Facades\LaravelDevelopment;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDevelopmentServiceProvider extends PackageServiceProvider
{
    /**
     * Configure the laravel development package.
     *
     * @link https://github.com/spatie/laravel-package-tools
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('ivirtual')
            ->hasConfigFile(['ivirtual', 'health'])
            ->hasViews()
            ->hasTranslations()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile();
            });
    }

    /**
     * Boot the package.
     */
    public function bootingPackage(): void
    {
        LaravelDevelopment::registerHealthChecks(
            $this->app->runningInConsole()
        );
    }
}
