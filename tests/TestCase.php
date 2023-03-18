<?php

namespace iVirtual\LaravelDevelopment\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use iVirtual\LaravelDevelopment\LaravelDevelopmentServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'iVirtual\\LaravelDevelopment\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDevelopmentServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-development_table.php.stub';
        $migration->up();
        */
    }
}
