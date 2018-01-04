<?php

namespace Space48\EnvironmentConfiguration\Test;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use PHPUnit\Framework\TestCase;
use Space48\EnvironmentConfiguration\ConfigValueRepository;
use Space48\EnvironmentConfiguration\Environment;
use Space48\EnvironmentConfiguration\EnvironmentConfigurationCommand;
use Space48\EnvironmentConfiguration\EnvironmentConfigValues;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnvironmentConfigurationCommandTest extends TestCase
{
    /** @var EnvironmentConfigurationCommand */
    private $subject;

    public function testConfigure()
    {
        self::assertEquals(
            EnvironmentConfigurationCommand::COMMAND_NAME,
            $this->subject->getName()
        );
    }

    public function testExecuteFailure()
    {
        $input = $this->getMockForAbstractClass(InputInterface::class);
        $output = $this->getMockForAbstractClass(OutputInterface::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->subject->execute($input, $output);
    }

    public function testExecuteSuccess()
    {
        $input = $this->getMockForAbstractClass(InputInterface::class);
        $output = $this->getMockForAbstractClass(OutputInterface::class);

        $input
            ->expects($this->any())->method('getArgument')
            ->with('environment')
            ->willReturn(Environment::STAGING);

        self::assertEquals(0, $this->subject->execute($input, $output));

    }

    protected function setUp()
    {
        $mockConfigWriter = new class implements WriterInterface
        {
            public function delete(
                $path,
                $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                $scopeId = 0
            ) {
            }

            public function save(
                $path,
                $value,
                $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                $scopeId = 0
            ) {
            }
        };

        $emptyConfig = EnvironmentConfigValues::create();

        $this->subject = new EnvironmentConfigurationCommand(
            new ConfigValueRepository($mockConfigWriter),
            $emptyConfig
        );
    }
}