<?php

namespace Symfony\Config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (FrameworkConfig $config, ContainerConfigurator $container) {
    $config->secret('%env(APP_SECRET)%')
        ->httpMethodOverride(false);

    $config->session()
        ->handlerId(null)
        ->cookieSecure('auto')
        ->cookieSamesite('lax')
        ->storageFactoryId('session.storage.factory.native');

    $config->phpErrors()
        ->log(true);

    $config->propertyAccess()
        ->enabled(true);

    if ('test' === $container->env()) {
        $config->test(true)
            ->session()
            ->storageFactoryId('session.storage.factory.mock_file');
    }
};
