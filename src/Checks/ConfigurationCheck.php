<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class ConfigurationCheck extends Check
{
    /**
     * Wheter should check if the configuration value equals or not the expected value.
     */
    private bool $checkEquals = true;

    /**
     * Configuration key name.
     */
    private string $configKey;

    /**
     * Configuration value.
     */
    private $expectedConfigValue;

    /**
     * Check if the given configuration key equals or not the given value.
     */
    public function run(): Result
    {
        if (empty($this->configKey) && empty($this->expectedConfigValue)) {
            return Result::make()->failed('Variables not provided.');
        }

        $actualConfigValue = config($this->configKey);

        $result = Result::make()->shortSummary($actualConfigValue);

        if ($this->checkEquals) {
            $result->meta([
                'actual' => $actualConfigValue,
                'expected' => $this->expectedConfigValue,
            ]);

            return $this->expectedConfigValue === $actualConfigValue
                ? $result->ok()
                : $result->failed('The environment was expected to be `:expected`, but actually was `:actual`');
        }

        $result->meta([
            'actual' => $actualConfigValue,
            'not_expected' => $this->expectedConfigValue,
        ]);

        return $this->expectedConfigValue !== $actualConfigValue
            ? $result->ok()
            : $result->failed('The environment was not expected to be `:not_expected`, but was `:actual`');
    }

    /**
     * Add a configuration variable to check.
     */
    public function configIs($key, $value): static
    {
        $this->configKey = $key;
        $this->expectedConfigValue = $value;

        return $this;
    }

    /**
     * Add a configuration variable to check.
     */
    public function configIsNot($key, $value): static
    {
        $this->checkEquals = false;

        $this->configIs($key, $value);

        return $this;
    }

    /**
     * Set the configuration check name.
     */
    public function name(string $name): static
    {
        $this->name = 'Config: '.$name;

        return $this;
    }
}
