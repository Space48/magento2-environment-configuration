<?php

namespace Space48\EnvironmentConfiguration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnvironmentConfigurationCommand extends Command
{
    const COMMAND_NAME = 'app:environment-configuration:apply';

    /** @var ConfigValueRepository */
    private $configValueRepository;

    /** @var EnvironmentConfigValues */
    private $environmentConfigValues;

    public function __construct(
        ConfigValueRepository $configValueRepository,
        EnvironmentConfigValues $environmentConfigValues,
        string $name = null
    ) {
        $this->configValueRepository = $configValueRepository;
        $this->environmentConfigValues = $environmentConfigValues;

        parent::__construct($name);
    }

    protected function configure()
    {
        parent::configure();

        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Apply non-sensitive environment specific config values');
        $this->addArgument(
            'environment',
            InputArgument::REQUIRED,
            'The environment to configure'
        );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $environment = $input->getArgument('environment');

        if (!Environment::isValid($environment)) {
            throw new \InvalidArgumentException(sprintf(
                'specified environment %s is invalid, accepted environments are %s.',
                $environment,
                implode(', ', Environment::all())
            ));
        }

        $environmentValues = $this->environmentConfigValues->getConfigValuesByEnvironment($environment);

        /** @var ConfigValue $configValue */
        foreach ($environmentValues as $configValue) {
            $this->configValueRepository->save($configValue);
        }

        $output->writeln(sprintf(
            'Updated config values for environment %s',
            $environment)
        );

        return 0;
    }


}