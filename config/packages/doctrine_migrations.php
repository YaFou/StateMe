<?php

namespace Symfony\Config;

return static function (DoctrineMigrationsConfig $config) {
    /** @psalm-suppress InvalidScalarArgument */
    $config->migrationsPath('DoctrineMigrations', '%kernel.project_dir%/migrations')
        ->enableProfiler('%kernel.debug%');
};
