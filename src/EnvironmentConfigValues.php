<?php

namespace Space48\EnvironmentConfiguration;

class EnvironmentConfigValues
{
    private $environments = [];

    public function withConfigValuesForEnvironment(ConfigValueSet $configValues, string $environment): self
    {
        $clone = clone $this;
        $clone->environments[$environment] = $configValues;
        return $clone;
    }

    public function getConfigValuesByEnvironment(string $environment): ConfigValueSet
    {
        return $this->environments[$environment] ?? ConfigValueSet::create();
    }

    public static function create(): self
    {
        return new self;
    }

    private function __construct()
    {
    }
}
