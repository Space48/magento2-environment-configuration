<?php

namespace Space48\EnvironmentConfiguration;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Scope
{
    private $scopeType;
    private $scopeCode;

    public function __construct(string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = 0)
    {
        $this->scopeType = $scopeType;
        $this->scopeCode = $scopeCode;
    }

    public function getScopeType(): string
    {
        return $this->scopeType;
    }

    public function getScopeCode()
    {
        return $this->scopeCode;
    }
}
