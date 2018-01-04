<?php

namespace Space48\EnvironmentConfiguration;


interface EnvironmentConfigValuesProvider
{
    public function getValues(): EnvironmentConfigValues;
}