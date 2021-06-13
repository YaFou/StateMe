<?php

namespace Symfony\Config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (TwigConfig $config, ContainerConfigurator $container) {
    $config->defaultPath('%kernel.project_dir%/templates');

    if ('test' === $container->env()) {
        $config->strictVariables(true);
    }
};
