<?php

namespace iVirtual\LaravelDevelopment;

use Illuminate\Support\Str;
use iVirtual\LaravelDevelopment\Checks\ConfigurationCheck;
use iVirtual\LaravelDevelopment\Checks\FlareCheck;
use iVirtual\LaravelDevelopment\Checks\FlareErrorOccurrenceCountCheck;
use iVirtual\LaravelDevelopment\Checks\LaravelNovaCheck;
use iVirtual\LaravelDevelopment\Checks\MailgunCheck;
use iVirtual\LaravelDevelopment\Checks\OhDearCheck;
use Spatie\Health\Checks\Checks\CacheCheck;
use Spatie\Health\Checks\Checks\DatabaseCheck;
use Spatie\Health\Checks\Checks\DatabaseConnectionCountCheck;
use Spatie\Health\Checks\Checks\DatabaseSizeCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\RedisCheck;
use Spatie\Health\Checks\Checks\RedisMemoryUsageCheck;
use Spatie\Health\Facades\Health;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

class LaravelDevelopment
{
    /**
     * Register the health checks that are required for deployment.
     */
    public function registerRequiredHealthChecks(): void
    {
        Health::checks([

            EnvironmentCheck::new(), // App environment should be 'production'.

            DebugModeCheck::new(), //Debug mode should be false.

            // App should not have laravel configuration default values.
            ...$this->getDefaultConfigurationChecks(),

            FlareCheck::new(), // Check that Flare is correctly configured.

            OhDearCheck::new(), // Check that Oh Dear is correctly configured.

            MailgunCheck::new(), // Check that Mailgun is correctly configured.

            LaravelNovaCheck::new()->daily(), // Check Laravel nova configuration.
        ]);
    }

    /**
     * Register the healt checks that will run on Oh Dear requests.
     */
    public function registerOhDearAppHealthChecks(): void
    {
        Health::checks([

            OptimizedAppCheck::new(), // App config, routes and events should be cached.

            FlareErrorOccurrenceCountCheck::new(), // Check for Flare occruence counts

            SecurityAdvisoriesCheck::new()->daily(), // Check for security vulnerabilities.

            CacheCheck::new(), // Check that cache is working.

            ...$this->getDatabaseChecks(),

            ...$this->getRedisChecks(),
        ]);
    }

    /**
     * Retrieve the laravel configuration value checks.
     */
    private function getDefaultConfigurationChecks(): array
    {
        return [

            ConfigurationCheck::new()
                ->configIsNot('app.name', 'Laravel')
                ->name('App name'),

            ConfigurationCheck::new()
                ->configIsNot('app.url', 'http://localhost')
                ->name('App url'),

            ConfigurationCheck::new()
                ->configIs('cache.default', 'redis')
                ->name('Default cache'),

            ConfigurationCheck::new()
                ->configIsNot('cache.prefix', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_')
                ->name('Cache prefix'),

            ConfigurationCheck::new()
                ->configIs('database.connections.mysql.host', config('ivirtual.config.mysql_host'))
                ->name('MySQL host'),

            ConfigurationCheck::new()
                ->configIs('database.connections.mysql.host', config('ivirtual.config.redis_host'))
                ->name('Redis default host'),

            ConfigurationCheck::new()
                ->configIs('database.connections.mysql.host', config('ivirtual.config.redis_host'))
                ->name('Redis cache host'),

            ConfigurationCheck::new()
                ->configIsNot(
                    'database.redis.options.prefix',
                    Str::slug(env('APP_NAME', 'laravel'), '_').'_cache_'
                )
                ->name('Redis Prefix'),

            ConfigurationCheck::new()
                ->configIs('queue.default', 'redis')
                ->name('Queue'),

            ConfigurationCheck::new()
                ->configIs('session.driver', 'redis')
                ->name('Session driver'),

            ConfigurationCheck::new()
                ->configIs('session.encrypt', true)
                ->name('Session encrypted'),

            ConfigurationCheck::new()
                ->configIsNot(
                    'session.cookie',
                    Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
                )
                ->name('Session cookie name'),

            ConfigurationCheck::new()
                ->configIs('session.secure', true)
                ->name('Session secure - Https Only'),

        ];
    }

    /**
     * Retrieve the database checks.
     */
    private function getDatabaseChecks(): array
    {
        return [
            // Database connection should be working.
            DatabaseCheck::new()
                ->name('Database'),

            // Check if database connections count are higher than usual.
            DatabaseConnectionCountCheck::new()
                ->warnWhenMoreConnectionsThan(15)
                ->failWhenMoreConnectionsThan(50),

            // Check if database size is more than 0.5 Gb.
            DatabaseSizeCheck::new()
                ->failWhenSizeAboveGb(errorThresholdGb: 0.5),
        ];
    }

    /**
     * Retrieve the Redis checks.
     */
    private function getRedisChecks(): array
    {
        return [
            // Redis connection should be working.
            RedisCheck::new(),

            // Check if redis memory ussage is more than 25 Mb.
            RedisMemoryUsageCheck::new()
                ->failWhenAboveMb(errorThresholdMb: 25),
        ];
    }
}
