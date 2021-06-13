<?php

namespace Symfony\Config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (FrameworkConfig $config, ContainerConfigurator $container) {
    $config->router()
        ->utf8(true);

    if ('prod' === $container->env()) {
        $config->router()
            ->strictRequirements(null);
    }
};
