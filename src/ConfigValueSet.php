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

            $instance->addConfigValue($configValue);
        }

        return $instance;
    }

    public function withConfigValue(ConfigValue $configValue): self
    {
        $instance = clone $this;
        $instance->addConfigValue($configValue);
        return $instance;
    }

    public function getIterator(): \ArrayIterator
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

    /**
     * @param $path
     * @param Scope|null $scope
     * @return ConfigValue
     */
    public function getConfigValueByPath($path, Scope $scope = null)
    {
        $scope = $scope ?? new Scope();
        $emptyConfig = (new ConfigValue($path, null))->withScope($scope);

        return $this->values[$this->getConfigValueUniqueKey($emptyConfig)] ?? $emptyConfig;
    }

    /**
     * Modify the state of the current instance and add a new config value to the
     * set.
     *
     * This should remain a private method to maintain the public immutable API.
     *
     * @param ConfigValue $configValue
     */
    private function addConfigValue(ConfigValue $configValue)
    {
        $this->values[$this->getConfigValueUniqueKey($configValue)] = $configValue;
    }

    /**
     * Generate unique key for a configuration value based on its path and scope.
     *
     * @param ConfigValue $configValue
     * @return string
     */
    private function getConfigValueUniqueKey(ConfigValue $configValue)
    {
        return join('/',[
            $configValue->getScope()->getScopeType(),
            $configValue->getScope()->getScopeCode(),
            $configValue->getPath()
        ]);
    }

    public function isEmpty(): bool
    {
        return empty($this->values);
    }
}