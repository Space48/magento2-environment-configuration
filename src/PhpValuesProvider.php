<?php

namespace Space48\EnvironmentConfiguration;

class PhpValuesProvider implements EnvironmentConfigValuesProvider
{
    /** @var string */
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getValues(): EnvironmentConfigValues
    {
        if (!is_file($this->filePath) || !is_readable($this->filePath)) {
            throw new \Exception(sprintf(
                'could not access file at %s',
                $this->filePath
            ));
        }

        $values = include $this->filePath;

        if (!$values instanceof EnvironmentConfigValues) {
            throw new \Exception(sprintf(
                'The file at %s was not eval\'d to an instance of %s',
                $this->filePath,
                EnvironmentConfigValues::class
            ));

        }

        return $values;
    }
}
