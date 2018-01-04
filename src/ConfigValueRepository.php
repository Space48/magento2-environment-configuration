<?php

namespace Space48\EnvironmentConfiguration;

use Magento\Framework\App\Config\Storage\WriterInterface as ConfigWriter;

class ConfigValueRepository
{
    /** @var ConfigWriter */
    private $configWriter;

    public function __construct(ConfigWriter $configWriter)
    {
        $this->configWriter = $configWriter;
    }

    public function save(ConfigValue $configValue)
    {
        $this->configWriter->save(
            $configValue->getPath(),
            $configValue->getValue(),
            $configValue->getScope()->getScopeType(),
            $configValue->getScope()->getScopeCode()
        );
    }
}