<?php

namespace Space48\EnvironmentConfiguration;


class ConfigValueSet implements \IteratorAggregate
{
    private $values = [];

    public static function of(array $configValues): self
    {
        $instance = new self;

        foreach ($configValues as $configValue) {
            if (!$configValue instanceof ConfigValue) {
                throw new \InvalidArgumentException(sprintf(
                    'Members of %s must be instances of %s, got %s',
                    self::class,
                    ConfigValue::class,
                    gettype($configValue)
                ));
            }

            $instance->values[$configValue->getPath()] = $configValue;
        }

        return $instance;
    }

    public function withConfigValue(ConfigValue $configValue): self
    {
        $instance = clone $this;
        $instance->values[$configValue->getPath()] = $configValue;
        return $instance;
    }

    public function getConfigValueByPath(string $path): ConfigValue
    {
        return $this->values[$path] ?? new ConfigValue($path, null);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }

    public static function create(): self
    {
        return new self;
    }

    private function __construct()
    {
    }

    public function isEmpty(): bool
    {
        return empty($this->values);
    }
}