<?php

namespace iVirtual\LaravelDevelopment;

use iVirtual\LaravelDevelopment\Facades\LaravelDevelopment;
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
            ->hasConfigFile()
            ->hasViews();
    }

    /**
     * Boot the package.
     */
    public function bootingPackage(): void
    {
        LaravelDevelopment::registerRequiredHealthChecks();

        if (! $this->app->runningInConsole()) {
            LaravelDevelopment::registerOhDearAppHealthChecks();
        }
    }
}
