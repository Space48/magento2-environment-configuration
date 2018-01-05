Magento 2 Environment Configuration
=

Different environments need different configuration and the words used to describe an environment tends to stay the same across different magento code bases ('production', 'staging', etc..) using these words we can store these values in the code and set them with a single command either during a deployment or when setting up a project for the first time.

Please note that the configuration values to be stored and consumed by this module are for convenience and shouldn't be used for handling passowords, keys or anything of a sensitive nature. If you need to store such things, either do it manually or use `env.php` or an environment variable.

Installation
==

Add this repository to your `composer.json` file:
```diff
"repositories": {
+    "space48-magento2-environment-configuration": {
+        "type": "git",
+        "url": "https://github.com/space48/magento2-environment-configuration.git"
+     }
}
  ```
  
Then `composer require` it and enable it:
```sh
composer require space48/magento2-environment-configuration
php bin/magento module:enable Space48_EnvironmentConfiguration
```
  
Usage
==

Day to day usage simply means running the command:
```sh
php bin/magento environment-configuration:apply
```

When setting up the package or adding new config values edit or create a file at `app/etc/environment-configuration.php` that looks like this:
  
```php
<?php

use Space48\EnvironmentConfiguration\ConfigValue;
use Space48\EnvironmentConfiguration\ConfigValueSet;
use Space48\EnvironmentConfiguration\Environment;
use Space48\EnvironmentConfiguration\EnvironmentConfigValues;

return EnvironmentConfigValues::create()
    ->withConfigValuesForEnvironment(
        $values = ConfigValueSet::of([
            // new ConfigValue('example/config/path', 'example config value')
        ]),
        $environment = Environment::LOCAL)
    ->withConfigValuesForEnvironment(
        $values = ConfigValueSet::of([
            // new ConfigValue('example/config/path', 'example config value')
        ]),
        $environment = Environment::PRODUCTION);
```

Contributing
==

If you find bugs, raise an issue or write a test to prove it, you can run the tests with a composer script:
```sh
composer test
```



