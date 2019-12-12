<?php

namespace Space48\EnvironmentConfiguration\Test;

use Magento\Framework\App\Config\{ScopeConfigInterface, Storage\WriterInterface};
use PHPUnit\Framework\TestCase;
use Space48\EnvironmentConfiguration\ConfigValue;
use Space48\EnvironmentConfiguration\ConfigValueRepository;
use Space48\EnvironmentConfiguration\Scope;

class ConfigValueRepositoryTest extends TestCase
{
    /**
     * @var ScopeConfigInterface|WriterInterface
     */
    private $configStub;

    protected function setUp()
    {
        $this->configStub = new class implements WriterInterface, ScopeConfigInterface
        {
            private $values = [];

            public function getValue($path, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = null)
            {
                return $this->values[$this->createKey($path, $scopeType, $scopeId)] ?? false;
            }

            public function isSetFlag($path, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = null)
            {
                return isset($this->values[$this->createKey($path, $scopeType, $scopeId)]);
            }

            public function delete($path, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
            {
                unset($this->values[$this->createKey($path, $scopeType, $scopeId)]);
            }

            public function save($path, $value, $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0)
            {
                $this->values[$this->createKey($path, $scopeType, $scopeId)] = $value;
            }

            private function createKey($path, $scopeType, $scopeId)
            {
                return join('/', [
                    $path, $scopeType ?? 'default', $scopeId ?? 0
                ]);
            }
        };
    }

    public function testSave()
    {
        $repository = new ConfigValueRepository($this->configStub);
        $repository->save(new ConfigValue('path', 'value'));

        self::assertEquals(
            'value',
            $this->configStub->getValue('path'),
            'We should be able to retrieve the saved config value by it\'s path.'
        );
    }

    public function testMultipleScopes()
    {
        $repository = new ConfigValueRepository($this->configStub);
        $repository->save(new ConfigValue('path', 'default value'));
        $repository->save((new ConfigValue('path', 'store 123 value'))->withScope(
            new Scope('store', 123)
        ));

        self::assertEquals(
            'default value',
            $this->configStub->getValue('path'),
            'We should be able to retrieve the saved config value by it\'s path for the default scope.'
        );

        self::assertEquals(
            'store 123 value',
            $this->configStub->getValue('path', 'store', 123),
            'We should be able to retrieve the saved config value by it\'s path for a specific store.'
        );
    }
}
