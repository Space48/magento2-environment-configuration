<?php

namespace Space48\EnvironmentConfiguration;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnvironmentConfigurationCommand extends Command
{
    const COMMAND_NAME = 'environment-configuration:apply';

    /** @var ConfigValueRepository */
    private $configValueRepository;

    /** @var EnvironmentConfigValuesProvider */
    private $environmentConfigValuesProvider;

    public function __construct(
        ConfigValueRepository $configValueRepository,
        EnvironmentConfigValuesProvider $environmentConfigValuesProvider,
        string $name = null
    ) {
        $this->configValueRepository = $configValueRepository;
        $this->environmentConfigValuesProvider = $environmentConfigValuesProvider;
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
                'Specified environment %s is invalid, accepted environments are %s.',
                $environment,
                implode(', ', Environment::all())
            ));
        }

        $environmentValues = $this
            ->environmentConfigValuesProvider
            ->getValues()
            ->getConfigValuesByEnvironment($environment);

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
