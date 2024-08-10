<?php

namespace iVirtual\LaravelDevelopment;

use Illuminate\Support\Str;
use iVirtual\LaravelDevelopment\Checks\ConfigurationCheck;
use iVirtual\LaravelDevelopment\Checks\HorizonCheck;
use iVirtual\LaravelDevelopment\Checks\LaravelNovaCheck;
use iVirtual\LaravelDevelopment\Checks\FilesystemCheck;
use iVirtual\LaravelDevelopment\Checks\LoggingCheck;
use iVirtual\LaravelDevelopment\Checks\MailCheck;
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
use Spatie\Health\Checks\Checks\ScheduleCheck;
use Spatie\SecurityAdvisoriesHealthCheck\SecurityAdvisoriesCheck;

class HealthChecks
{
    /**
     * Retrieve an array with all the health checks.
     */
    public static function new(): array
    {
        return [
            ...static::configurationChecks(),
            ...static::servicesChecks(),
        ];
    }

    /**
     * Retrieve all configuration checks.
     */
    public static function configurationChecks(): array
    {
        return [

            // App config, routes and events should be cached.
            OptimizedAppCheck::new(),

            // App environment should be 'production'.
            EnvironmentCheck::new(),

            //Debug mode should be false.
            DebugModeCheck::new(),

            // App should not have laravel configuration default values.
            ...static::getDefaultConfigurationChecks(),

            // Check that Oh Dear is correctly configured.
            OhDearCheck::new(),

            // Check that Sentry is correctly configured.
            FilesystemCheck::new(),

            // Check that Mailgun is correctly configured.
            MailCheck::new(),

            // Check that Mailgun is correctly configured.
            LoggingCheck::new(),

            // Check Laravel nova configuration.
            LaravelNovaCheck::new()->daily(),
        ];
    }

    /**
     * Retrieve all external services checks.
     */
    public static function servicesChecks(): array
    {
        return [

            SecurityAdvisoriesCheck::new()->daily(), // Check for security vulnerabilities.

            CacheCheck::new(), // Check that cache is working.

            ...static::getDatabaseChecks(),

            ...static::getRedisChecks(),

            HorizonCheck::new(),

            ScheduleCheck::new(),
        ];
    }

    /**
     * Retrieve the laravel configuration value checks.
     */
    private static function getDefaultConfigurationChecks(): array
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
                ->configIsNot('cache.prefix', 'laravel_cache_')
                ->name('Cache prefix'),

            ConfigurationCheck::new()
                ->configIs('database.connections.mysql.host', config('ivirtual.config.mysql_host'))
                ->name('MySQL host'),

            ConfigurationCheck::new()
                ->configIsNot('database.redis.options.prefix', 'laravel_database_')
                ->name('Redis Prefix'),

            ConfigurationCheck::new()
                ->configIs('database.redis.default.host', config('ivirtual.config.redis_host'))
                ->name('Redis default host'),

            ConfigurationCheck::new()
                ->configIs('database.redis.cache.host', config('ivirtual.config.redis_host'))
                ->name('Redis cache host'),

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
                    Str::slug(env('APP_NAME', 'laravel'), '_') . '_session'
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
    private static function getDatabaseChecks(): array
    {
        return [
            // Database connection should be working.
            DatabaseCheck::new()
                ->name('Database'),

            // Check if database connections count are higher than usual.
            DatabaseConnectionCountCheck::new()
                ->warnWhenMoreConnectionsThan(50)
                ->failWhenMoreConnectionsThan(100),

            // Check if database size is more than 0.5 Gb.
            DatabaseSizeCheck::new()
                ->failWhenSizeAboveGb(errorThresholdGb: config('ivirtual.database.size')),
        ];
    }

    /**
     * Retrieve the Redis checks.
     */
    private static function getRedisChecks(): array
    {
        return [
            // Redis connection should be working.
            RedisCheck::new(),

            // Check if redis memory ussage is more than 500 Mb.
            // Current server has 1gb of memory.
            RedisMemoryUsageCheck::new()
                ->failWhenAboveMb(errorThresholdMb: 500),
        ];
    }
}
