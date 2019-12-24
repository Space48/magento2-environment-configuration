<?php

namespace Space48\EnvironmentConfiguration\Test;

use PHPUnit\Framework\TestCase;
use Space48\EnvironmentConfiguration\ConfigValue;
use Space48\EnvironmentConfiguration\ConfigValueSet;
use Space48\EnvironmentConfiguration\Scope;

class ConfigValueSetTest extends TestCase
{
    public function testOneValuePerPathScopePair()
    {
        $set = ConfigValueSet::of([
            (new ConfigValue('example/path', 'hello')),
            (new ConfigValue('example/path', 'world'))->withScope(
                new Scope('store', 999)
            ),
            (new ConfigValue('example/path', 'overwrite'))->withScope(
                new Scope('store', 999)
            ),
        ]);

        self::assertCount(2, $set->getIterator());
        self::assertEquals('hello', $set->getConfigValueByPath('example/path')->getValue());
        self::assertEquals('overwrite', $set->getConfigValueByPath('example/path', new Scope('store', 999))->getValue());
    }
}