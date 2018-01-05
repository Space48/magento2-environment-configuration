<?php

namespace Space48\EnvironmentConfiguration;

final class Environment
{
    const
        PRODUCTION = 'production',
        STAGING = 'staging',
        DEVELOPMENT = 'development',
        LOCAL = 'local'
    ;

    private function __construct()
    {
    }

    public static function isValid($environment): bool
    {
        return $environment === self::PRODUCTION
            || $environment === self::STAGING
            || $environment === self::DEVELOPMENT
            || $environment === self::LOCAL;
    }

    public static function all(): array
    {
        return [
            self::PRODUCTION,
            self::STAGING,
            self::DEVELOPMENT,
            self::LOCAL
        ];
    }
}
