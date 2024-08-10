<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Illuminate\Support\Arr;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class FilesystemCheck extends Check
{
    /**
     * Check if Flare (Spatie Ignition) is installed and the correctly configured.
     */
    public function run(): Result
    {
        if (is_null(config('filesystems.default'))) {
            return Result::make()->ok('App does not uses files.');
        }

        if (config('filesystems.default') !== 'linode') {
            return Result::make()->failed('Filesystem is not linode.');
        }

        if ($this->linodeVariablesNotSet()) {
            return Result::make()->failed('Linode configuration variables are not set.');
        }

        return Result::make()->ok();
    }

    /**
     * Check if linode variables are not set.
     */
    private function linodeVariablesNotSet(): bool
    {
        $configuration = config('filesystems.disks.linode', []);

        return is_null(Arr::get($configuration, 'key'))
            || is_null(Arr::get($configuration, 'secret'))
            || is_null(Arr::get($configuration, 'region'))
            || is_null(Arr::get($configuration, 'bucket'))
            || is_null(Arr::get($configuration, 'endpoint'));
    }
}
