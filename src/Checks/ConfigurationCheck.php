<?php

namespace iVirtual\LaravelDevelopment\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class ConfigurationCheck extends Check
{
    private bool $checkEquals = true;

    private string $configKey;

    private $expectedConfigValue;

    /**
     * Check if Laravel Nova is installed and the license is correctly configured.
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
     * Add a new variable to check.
     */
    public function configIs($key, $value): self
    {
        $this->configKey = $key;
        $this->expectedConfigValue = $value;

        return $this;
    }

    /**
     * Add a new variable to check.
     */
    public function configIsNot($key, $value): self
    {
        $this->checkEquals = false;

        $this->configIs($key, $value);

        return $this;
    }

    /**
     * Set the configuration check name.
     */
    public function name(string $name): self
    {
        $this->name = 'Configuration check: '.$name;

        return $this;
    }
}
