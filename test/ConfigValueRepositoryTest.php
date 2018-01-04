<?php

namespace Space48\EnvironmentConfiguration\Test;

use Magento\Framework\App\Config\{ScopeConfigInterface, Storage\WriterInterface};
use PHPUnit\Framework\TestCase;
use Space48\EnvironmentConfiguration\ConfigValue;
use Space48\EnvironmentConfiguration\ConfigValueRepository;

class ConfigValueRepositoryTest extends TestCase
{
    public function testSave()
    {
        $config = new class implements WriterInterface, ScopeConfigInterface
        {
            private $values = [];

            public function getValue($path, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
            {
                return $this->values[$path] ?? false;
            }

            public function isSetFlag($path, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeCode = null)
            {
                return isset($this->values[$path]);
            }

            public function delete($path, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
            {
                unset($this->values[$path]);
            }

            public function save($path, $value, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
            {
                $this->values[$path] = $value;
            }
        };

        $repository = new ConfigValueRepository($config);
        $repository->save(new ConfigValue('path', 'value'));

        self::assertEquals(
            'value',
            $config->getValue('path'),
            'We should be able to retrieve the saved config value by it\'s path.'
        );
    }
}