<?php

namespace Space48\EnvironmentConfiguration;

use Magento\Framework\App\Config\ScopeConfigInterface;

class ConfigValue
{
    private $path;
    private $value;
    private $scope;

    public function __construct(string $path, $value)
    {
        $this->path = $path;
        $this->value = $value;
    }

    public function isNull(): bool
    {
        return $this->value === null;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function withScope(Scope $scope): self
    {
        $result = clone $this;
        $result->scope = $scope->getScopeType();
        return $result;
    }

    public function getScope(): Scope
    {
        return $this->scope ?? new Scope(ScopeConfigInterface::SCOPE_TYPE_DEFAULT, null);
    }
}
