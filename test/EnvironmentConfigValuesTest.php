<?php

namespace Space48\EnvironmentConfiguration\Test;

use PHPUnit\Framework\TestCase;
use Space48\EnvironmentConfiguration\ConfigValue;
use Space48\EnvironmentConfiguration\ConfigValueSet;
use Space48\EnvironmentConfiguration\Environment;
use Space48\EnvironmentConfiguration\EnvironmentConfigValues;

class EnvironmentConfigValuesTest extends TestCase
{
    public function testGetConfigValuesByEnvironment()
    {
        $path = 'space48/module/config';
        $localConfigValue = new ConfigValue($path, 'localValue');
        $productionConfigValue = new ConfigValue($path, 'productionValue');

        $environmentConfig = EnvironmentConfigValues::create()
            ->withConfigValuesForEnvironment(
                ConfigValueSet::create()->withConfigValue($productionConfigValue),
                Environment::PRODUCTION)
            ->withConfigValuesForEnvironment(
                ConfigValueSet::create()->withConfigValue($localConfigValue),
                Environment::LOCAL);

        self::assertEquals(
            'productionValue',
            $environmentConfig
                ->getConfigValuesByEnvironment(Environment::PRODUCTION)
                ->getConfigValueByPath($path)->getValue(),
            'The production environment should return the production value.'
        );

        self::assertEquals(
            'localValue',
            $environmentConfig
                ->getConfigValuesByEnvironment(Environment::LOCAL)
                ->getConfigValueByPath($path)->getValue(),
            'The local environment should return the local value.'
        );

        self::assertTrue(
            $environmentConfig
                ->getConfigValuesByEnvironment(Environment::DEVELOPMENT)
                ->getConfigValueByPath($path)->isNull(),
            'The development environment should not be set.'
        );
    }
}
